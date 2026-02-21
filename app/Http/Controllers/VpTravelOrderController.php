<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Models\TravelOrder;
use App\Models\Employee;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\JsonResponse;

class VpTravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders for VP approval with tab support.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Ensure the user is a VP
        if (!$employee->is_vp) {
            abort(403);
        }
        
        // All VPs can access travel order approvals now
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Find the office_id of the current employee's primary position
        $primaryPosition = $employee->positions()->where('is_primary', true)->first();
        $officeId = $primaryPosition ? $primaryPosition->office_id : null;
        
        $query = TravelOrder::where(function ($query) {
                // Include division head travel orders that need VP approval (division heads approve their own)
                $query->whereHas('employee.officer', function ($officerQuery) {
                    $officerQuery->where('division_head', true)
                          ->where('vp', false)
                          ->where('president', false);
                })
                ->where(function($subQuery) {
                    // Division head travel orders don't need head approval, they go directly to VP
                    $subQuery->whereNull('vp_approved');
                })
                ->orWhere(function ($orQuery) {
                    // Include unit head travel orders that have been approved by division head
                    $orQuery->whereHas('employee.officer', function ($officerQuery) {
                        $officerQuery->where('unit_head', true)
                              ->where('division_head', false)
                              ->where('vp', false)
                              ->where('president', false);
                    })
                    ->where('divisionhead_approved', true);
                });
            })
            ->where(function ($positionQuery) use ($officeId) {
                // Either in the same office as the VP
                $positionQuery->whereHas('position', function ($posQuery) use ($officeId) {
                    $posQuery->where('office_id', $officeId);
                })
                // OR escalate to President if no VP in same office
                ->orWhere(function ($orQuery) use ($officeId) {
                    // Check if there's no VP in the same office
                    $vpInSameOffice = \App\Models\Employee::whereHas('positions', function($query) use ($officeId) {
                        $query->where('is_vp', true)
                              ->where('office_id', $officeId);
                    })->exists();
                    
                    // If no VP in same office, escalate to President
                    if (!$vpInSameOffice) {
                        // Check if there's a President
                        $presidentExists = \App\Models\Employee::whereHas('positions', function($query) {
                            $query->where('is_president', true);
                        })->exists();
                        
                        // If President exists, include all travel orders for President approval
                        if ($presidentExists) {
                            $orQuery->whereNotNull('divisionhead_approved') // Only approved by division head
                                    ->whereNull('vp_approved') // Not yet approved by VP
                                    ->whereNull('president_approved'); // Not yet approved by President
                        } else {
                            // No President either - include nothing (this shouldn't happen)
                            $orQuery->whereRaw('1 = 0');
                        }
                    } else {
                        // VP exists in same office - don't escalate
                        $orQuery->whereRaw('1 = 0');
                    }
                });
            });
        
        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('destination', 'LIKE', "%{$search}%")
                  ->orWhere('purpose', 'LIKE', "%{$search}%")
                  ->orWhere('remarks', 'LIKE', "%{$search}%")
                  ->orWhereHas('employee', function($q) use ($search) {
                      $q->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        switch ($tab) {
            case 'approved':
                $query->where('vp_approved', true);
                break;
            case 'cancelled':
                $query->where('vp_approved', false);
                break;
            case 'pending':
            default:
                $query->where('vp_approved', null);
                break;
        }
        
        // Get paginated results with position information
        $travelOrders = $query->with('position', 'employee')->orderBy('created_at', 'desc')->paginate(10)->appends($request->except('page'));
        
        // Check if this is an AJAX request for partial updates
        if ($request->ajax() || $request->get('ajax')) {
            return response()->json([
                'table_body' => view('travel-orders.approvals.partials.table-rows', compact('travelOrders', 'tab'))->with('travelOrders', $travelOrders->load('position', 'employee'))->render(),
                'pagination' => (string) $travelOrders->withQueryString()->links()
            ]);
        }
        
        return view('travel-orders.approvals.vp-index', compact('travelOrders', 'tab', 'search'));
    }

    /**
     * Display the specified resource for approval.
     */
    public function show(TravelOrder $travelOrder): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Ensure the user is a VP
        if (!$employee->is_vp) {
            abort(403);
        }
        
        // All VPs can access travel order approvals now
        
        // Check if employee exists
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an employee record. Please contact your administrator to set up your employee profile.');
        }
        
        // Check if the travel order belongs to an employee in the VP's office
        $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $vpOfficeId = $vpPrimaryPosition ? $vpPrimaryPosition->office_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderOfficeId = $travelOrderPosition->office_id;
        $travelOrderDivisionId = $travelOrderPosition->division_id;
        
        // For unit head travel orders, check if they're from the same division
        $isFromDivision = false;
        if ($travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
            $vpDivisionId = $vpPrimaryPosition ? $vpPrimaryPosition->division_id : null;
            $isFromDivision = $travelOrderDivisionId === $vpDivisionId;
        }
        
        // Allow if travel order is from employee in VP's office
        $isFromOffice = $travelOrderOfficeId === $vpOfficeId;
        
        // Also allow if it's the VP's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        if (!$isFromOffice && !$isFromDivision && !$isOwn) {
            abort(403);
        }
        
        // Set approval context flag
        $approvalContext = true;
        
        return view('travel-orders.show', compact('travelOrder', 'approvalContext'));
    }
    
    /**
     * Approve a travel order.
     */
    public function approve(TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Ensure the user is a VP
        if (!$employee->is_vp) {
            abort(403);
        }
        
        // All VPs can approve travel orders now
        
        // Ensure the VP can only approve travel orders from their office
        $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $vpOfficeId = $vpPrimaryPosition ? $vpPrimaryPosition->office_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderOfficeId = $travelOrderPosition->office_id;
        
        if ($travelOrderOfficeId !== $vpOfficeId) {
            abort(403);
        }
        
        // Ensure the travel order is from a division head or unit head
        if (!$travelOrder->employee || (!$travelOrder->employee->is_divisionhead && !$travelOrder->employee->is_head)) {
            abort(403);
        }
        
        // For unit head travel orders, ensure division head has approved
        // For division head travel orders, no head approval needed (they approve their own)
        if ($travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            // Unit head travel order - check division head approval
            if (!$travelOrder->divisionhead_approved) {
                abort(403);
            }
        } elseif ($travelOrder->employee->is_divisionhead) {
            // Division head travel order - no head approval needed, they go directly to VP
            // Just ensure this is the right stage (not yet approved by VP)
            if (!is_null($travelOrder->vp_approved)) {
                abort(403);
            }
        } else {
            // Regular employee travel order - check head approval
            if (!$travelOrder->head_approved) {
                abort(403);
            }
        }
        
        // Ensure the travel order hasn't already been approved by VP
        if (!is_null($travelOrder->vp_approved)) {
            abort(403);
        }
        
        // Ensure the travel order is still at the VP approval stage (not yet approved by president)
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Approve the travel order
        $travelOrder->update([
            'vp_approved' => true,
            'vp_approved_at' => now(),
            'status' => $travelOrder->employee->is_divisionhead ? 'pending' : 'approved', // Division heads need president approval, others are approved
        ]);

        return redirect()->back()
            ->with('success', 'Travel order approved successfully.');
    }

    /**
     * Reject a travel order.
     */
    public function reject(TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Ensure the user is a VP
        if (!$employee->is_vp) {
            abort(403);
        }
        
        // Additional check: Only VPs from Office of the Vice President for Administration can reject
        $isVpOfAdministration = $employee->positions()->whereHas('office', function($query) {
            $query->where('office_name', 'Office of the Vice President for Administration');
        })->exists();
        
        if (!$isVpOfAdministration) {
            abort(403, 'Only VP of Office of the Vice President for Administration can reject travel orders');
        }
        
        // Ensure the VP can only reject travel orders from their office
        $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $vpOfficeId = $vpPrimaryPosition ? $vpPrimaryPosition->office_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderOfficeId = $travelOrderPosition->office_id;
        $travelOrderDivisionId = $travelOrderPosition->division_id;
        
        // For unit head travel orders, check if they're from the same division
        $isFromDivision = false;
        if ($travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
            $vpDivisionId = $vpPrimaryPosition ? $vpPrimaryPosition->division_id : null;
            $isFromDivision = $travelOrderDivisionId === $vpDivisionId;
        }
        
        // Allow if travel order is from employee in VP's office
        $isFromOffice = $travelOrderOfficeId === $vpOfficeId;
        
        // Also allow if it's the VP's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        if ($travelOrderOfficeId !== $vpOfficeId && !$isFromDivision && !$isOwn) {
            abort(403);
        }
        
        // Ensure the travel order is from a division head or unit head
        if (!$travelOrder->employee || (!$travelOrder->employee->is_divisionhead && !$travelOrder->employee->is_head)) {
            abort(403);
        }
        
        // For unit head travel orders, ensure division head has approved
        // For division head travel orders, no head approval needed (they approve their own)
        if ($travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            // Unit head travel order - check division head approval
            if (!$travelOrder->divisionhead_approved) {
                abort(403);
            }
        } elseif ($travelOrder->employee->is_divisionhead) {
            // Division head travel order - no head approval needed, they go directly to VP
            // Just ensure this is the right stage (not yet approved by VP)
            if (!is_null($travelOrder->vp_approved)) {
                abort(403);
            }
        } else {
            // Regular employee travel order - check head approval
            if (!$travelOrder->head_approved) {
                abort(403);
            }
        }
        
        // Ensure the travel order hasn't already been approved by VP
        if (!is_null($travelOrder->vp_approved)) {
            abort(403);
        }
        
        // Ensure the travel order is still at the VP approval stage (not yet approved by president)
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Reject the travel order
        $travelOrder->update([
            'vp_approved' => false,
            'vp_approved_at' => now(),
            'status' => 'cancelled',
        ]);

        return redirect()->back()
            ->with('success', 'Travel order rejected successfully.');
    }
    
    /**
     * Generate PDF for travel order in approval context
     */
    public function generatePDF(TravelOrder $travelOrder): Response
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if employee exists
        if (!$employee) {
            abort(403);
        }
        
        // Check if the travel order belongs to an employee in the VP's office
        $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $vpOfficeId = $vpPrimaryPosition ? $vpPrimaryPosition->office_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderOfficeId = $travelOrderPosition->office_id;
        $travelOrderDivisionId = $travelOrderPosition->division_id;
        
        // For unit head travel orders, check if they're from the same division
        $isFromDivision = false;
        if ($travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
            $vpDivisionId = $vpPrimaryPosition ? $vpPrimaryPosition->division_id : null;
            $isFromDivision = $travelOrderDivisionId === $vpDivisionId;
        }
        
        // Allow if travel order is from employee in VP's office
        $isFromOffice = $travelOrderOfficeId === $vpOfficeId;
        
        // Also allow if it's the VP's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        if (!$isFromOffice && !$isFromDivision && !$isOwn) {
            abort(403);
        }
        
        // Load the travel order with all necessary relationships
        $travelOrder->load([
            'employee', 
            'employee.user',
            'employee.position',
            'employee.office',
            'employee.division',
            'employee.unit'
        ]);
        
        // Get employee information
        $employee = $travelOrder->employee;
        $fullName = $employee->first_name . ' ' . $employee->last_name;
        $positionName = $employee->position ? $employee->position->position_name : $employee->position_name;
        
        // Determine organizational unit name
        $orgUnitName = '';
        if ($employee->unit) {
            $orgUnitName = $employee->unit->unit_name;
        } elseif ($employee->division) {
            $orgUnitName = $employee->division->division_name;
        } elseif ($employee->office) {
            $orgUnitName = $employee->office->office_name;
        }
        
        // Get approver information for different employee types
        $supervisorName = 'N/A';
        $supervisorPosition = 'Supervisor';
        
        if ($employee->is_head && !$employee->is_divisionhead) {
            // Unit head - approver is Division Head
            $approverEmployee = $this->getApproverEmployee('DIVISION_HEAD', $employee->position);
            $supervisorName = $approverEmployee ? $approverEmployee->first_name . ' ' . $approverEmployee->last_name : 'N/A';
            $supervisorPosition = $approverEmployee && $approverEmployee->position ? $approverEmployee->position->position_name : 'Division Head';
        } elseif ($employee->is_divisionhead) {
            // Division head - no immediate supervisor, goes directly to VP
            $supervisorName = '';
            $supervisorPosition = '';
        }
        
        // Get higher rank officer (President)
        $higherRankOfficer = $this->getApproverEmployee('PRESIDENT', $employee->position);
        $higherRankName = $higherRankOfficer ? $higherRankOfficer->first_name . ' ' . $higherRankOfficer->last_name : 'N/A';
        $higherRankPosition = $higherRankOfficer && $higherRankOfficer->position ? $higherRankOfficer->position->position_name : 'President';
        
        // Load Excel template
        $templatePath = public_path('templates/travel_order_template.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        // Fill in the basic travel order information
        $sheet->setCellValue('C9', $fullName);
        $sheet->setCellValue('C10', $travelOrder->purpose);
        $sheet->setCellValue('G11', $travelOrder->destination);
        $sheet->setCellValue('E23', $fullName);
        $sheet->setCellValue('E24', $positionName);
        $sheet->setCellValue('E25', $orgUnitName);
        $sheet->setCellValue('E26', $travelOrder->purpose);
        $sheet->setCellValue('B33', $travelOrder->date_from ? $travelOrder->date_from->format('F j, Y') : '');
        $sheet->setCellValue('B35', $travelOrder->date_to ? $travelOrder->date_to->format('F j, Y') : '');
        $sheet->setCellValue('D34', $travelOrder->destination);
        $sheet->setCellValue('G34', $travelOrder->departure_time ?? '');
        $sheet->setCellValue('H34', '');
        
        // Fill in approver information
        $sheet->setCellValue('H19', $supervisorName);
        $sheet->setCellValue('H20', $supervisorPosition);
        $sheet->setCellValue('I41', $fullName);
        $sheet->setCellValue('I42', $positionName);
        $sheet->setCellValue('I45', $higherRankName);
        $sheet->setCellValue('I46', $higherRankPosition);
        
        // Add approval status in cells I17 (Division Head/Unit Head) and K43 (VP/President)
        // I17: Show only when Division Head or Unit Head has approved/declined
        if ($travelOrder->divisionhead_approved_at) {
            $divisionHeadStatus = $travelOrder->divisionhead_approved ? 'APPROVED' : 'DECLINED';
            $divisionHeadTimestamp = $travelOrder->divisionhead_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('H17', $divisionHeadStatus . ' ' . $divisionHeadTimestamp);
        } elseif ($travelOrder->head_approved_at) {
            $unitHeadStatus = $travelOrder->head_approved ? 'APPROVED' : 'DECLINED';
            $unitHeadTimestamp = $travelOrder->head_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('H17', $unitHeadStatus . ' ' . $unitHeadTimestamp);
        }
        
        // K43: Show VP approval status when available
        if ($travelOrder->vp_approved_at) {
            $vpStatus = $travelOrder->vp_approved ? 'APPROVED' : 'DECLINED';
            $vpTimestamp = $travelOrder->vp_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('I43', $vpStatus . ' ' . $vpTimestamp);
        }
        
        // For President approval, show in K43 if no VP approval
        if ($travelOrder->president_approved_at && !$travelOrder->vp_approved_at) {
            $presidentStatus = $travelOrder->president_approved ? 'APPROVED' : 'DECLINED';
            $presidentTimestamp = $travelOrder->president_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('I43', $presidentStatus . ' ' . $presidentTimestamp);
        }
        
        // Save Excel temporarily for conversion
        $excelTemp = tempnam(sys_get_temp_dir(), 'travel_order_') . '.xlsx';
        IOFactory::createWriter($spreadsheet, 'Xlsx')->save($excelTemp);
        
        // Check if ConvertAPI is configured
        $convertApiSecret = config('services.convertapi.secret');
        
        if (!empty($convertApiSecret)) {
            try {
                // Use ConvertAPI to convert Excel to PDF
                ConvertApi::setApiCredentials($convertApiSecret);
                $result = ConvertApi::convert('pdf', ['File' => $excelTemp], 'xlsx');
                
                // Get the PDF content
                $pdfContent = $result->getFile()->getContents();
                
                // Clean up temporary files
                if (file_exists($excelTemp)) {
                    unlink($excelTemp);
                }
            } catch (\Exception $e) {
                // Clean up the Excel temp file in case of error
                if (file_exists($excelTemp)) {
                    unlink($excelTemp);
                }
                
                // Fallback to MPDF
                try {
                    $tempPdfPath = storage_path('app/temp_approval_travel_order_' . $travelOrder->id . '.pdf');
                    
                    // Use the MPDF library directly with PhpSpreadsheet
                    $pdfWriter = IOFactory::createWriter($spreadsheet, 'Mpdf');
                    $pdfWriter->save($tempPdfPath);
                    
                    // Read the generated PDF content
                    $pdfContent = file_get_contents($tempPdfPath);
                    
                    // Clean up temporary file
                    unlink($tempPdfPath);
                } catch (\Exception $mpdfException) {
                    return response()->json([
                        'error' => 'PDF generation failed.',
                        'message' => 'Both ConvertAPI and MPDF failed: ' . $mpdfException->getMessage(),
                    ], 500);
                }
            }
        } else {
            // Fallback to MPDF if ConvertAPI is not configured
            try {
                $tempPdfPath = storage_path('app/temp_approval_travel_order_' . $travelOrder->id . '.pdf');
                
                // Use the MPDF library directly with PhpSpreadsheet
                $pdfWriter = IOFactory::createWriter($spreadsheet, 'Mpdf');
                $pdfWriter->save($tempPdfPath);
                
                // Read the generated PDF content
                $pdfContent = file_get_contents($tempPdfPath);
                
                // Clean up temporary file
                unlink($tempPdfPath);
                
                // Clean up the Excel temp file as well
                if (file_exists($excelTemp)) {
                    unlink($excelTemp);
                }
            } catch (\Exception $e) {
                // Clean up temporary files in case of error
                if (file_exists($excelTemp)) {
                    unlink($excelTemp);
                }
                if (file_exists($tempPdfPath)) {
                    unlink($tempPdfPath);
                }
                
                return response()->json([
                    'error' => 'PDF generation failed.',
                    'message' => 'MPDF failed: ' . $e->getMessage(),
                ], 500);
            }
        }
        
        // Return PDF response
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="travel_order_' . $travelOrder->id . '.pdf"');
    }
    
    /**
     * Get the actual approver employee based on role and position
     */
    private function getApproverEmployee($approverRole, $employeePosition)
    {
        switch ($approverRole) {
            case 'DIVISION_HEAD':
                return \App\Models\Employee::whereHas('positions', function($query) use ($employeePosition) {
                    $query->where('is_division_head', true)
                          ->where('division_id', $employeePosition->division_id);
                })->first();
                
            case 'UNIT_HEAD':
                return \App\Models\Employee::whereHas('positions', function($query) use ($employeePosition) {
                    $query->where('is_unit_head', true)
                          ->where('unit_id', $employeePosition->unit_id);
                })->first();
                
            case 'PRESIDENT':
                return \App\Models\Employee::whereHas('officer', function($query) {
                    $query->where('president', true);
                })->first();
                
            default:
                return null;
        }
    }
    
    /**
     * Get travel order details for modal display (approval context)
     */
    public function showDetails(TravelOrder $travelOrder): JsonResponse
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if employee exists
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee record not found'
            ], 404);
        }
        
        // Check if this travel order is from someone in the VP's office (approval context)
        $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $vpOfficeId = $vpPrimaryPosition ? $vpPrimaryPosition->office_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        $travelOrderOfficeId = $travelOrderPosition ? $travelOrderPosition->office_id : null;
        
        $isFromOffice = $travelOrderOfficeId === $vpOfficeId;
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        // Allow access if it's from their office or their own travel order
        if (!$isFromOffice && !$isOwn) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to travel order'
            ], 403);
        }
        
        // Load relationships
        $travelOrder->load(['employee', 'position']);
        
        return response()->json([
            'success' => true,
            'data' => $travelOrder
        ]);
    }
}