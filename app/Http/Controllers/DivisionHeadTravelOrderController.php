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

class DivisionHeadTravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders for division head approval with tab support.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Find the division_id of the current employee's primary position
        $primaryPosition = $employee->positions()->where('is_primary', true)->first();
        $divisionId = $primaryPosition ? $primaryPosition->division_id : null;
        
        $query = TravelOrder::whereHas('employee', function ($employeeQuery) {
                $employeeQuery->where(function ($empSubQuery) {
                    // Include regular employees (without officer records)
                    $empSubQuery->whereDoesntHave('officer')
                    ->orWhereHas('officer', function ($officerQuery) {
                        $officerQuery->where(function ($roleQuery) {
                            // Include regular employees with no leadership roles
                            $roleQuery->where('unit_head', false)
                                  ->where('division_head', false)
                                  ->where('vp', false)
                                  ->where('president', false);
                        })
                        ->orWhere(function ($roleQuery) {
                            // Include unit heads
                            $roleQuery->where('unit_head', true)
                                  ->where('division_head', false)
                                  ->where('vp', false)
                                  ->where('president', false);
                        });
                    });
                });
            })
            ->whereHas('position', function ($positionQuery) use ($divisionId) {
                $positionQuery->where('division_id', $divisionId);
            })
            ->where(function ($q) {
                // For regular employees: head must be approved
                $q->where(function ($subQ) {
                    $subQ->whereHas('employee.officer', function ($officerQuery) {
                        $officerQuery->where('unit_head', false)
                              ->where('division_head', false)
                              ->where('vp', false)
                              ->where('president', false);
                    })
                    ->where('head_approved', true);
                })
                ->orWhere(function ($subQ) {
                    // For employees without officer records (regular employees): head must be approved
                    $subQ->whereDoesntHave('employee.officer')
                    ->where('head_approved', true);
                })
                ->orWhere(function ($orQ) {
                    // For unit heads with officer records: no head approval needed
                    $orQ->whereHas('employee.officer', function ($officerQuery) {
                        $officerQuery->where('unit_head', true)
                              ->where('division_head', false)
                              ->where('vp', false)
                              ->where('president', false);
                    });
                    // No additional approval needed before division head for unit heads
                })
                ->orWhere(function ($orQ) {
                    // For unit heads without officer records: no head approval needed
                    $orQ->whereDoesntHave('employee.officer')
                    ->whereHas('employee.positions', function ($positionQuery) {
                        $positionQuery->where('is_unit_head', true);
                    });
                    // No additional approval needed before division head for unit heads
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
        
        // Apply tab-specific filtering
        switch ($tab) {
            case 'approved':
                $query->where('divisionhead_approved', true);  // Approved by division head
                break;
            case 'cancelled':
                $query->where('divisionhead_approved', false);
                break;
            case 'pending':
            default:
                $query->whereNull('divisionhead_approved');  // Not yet approved by division head
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
        
        return view('travel-orders.approvals.divisionhead-index', compact('travelOrders', 'tab', 'search'));
    }

    /**
     * Display the specified resource for approval.
     */
    public function show(TravelOrder $travelOrder): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if employee exists
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an employee record. Please contact your administrator to set up your employee profile.');
        }
        
        // Check if the travel order belongs to an employee in the division head's division
        $divisionHeadPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $divisionHeadDivisionId = $divisionHeadPrimaryPosition ? $divisionHeadPrimaryPosition->division_id : null;
        
        // Get the employee's primary position division instead of travel order position
        $employeePrimaryPosition = $travelOrder->employee->positions()->where('is_primary', true)->first();
        
        // If no primary position is assigned to the employee, deny access
        if (!$employeePrimaryPosition) {
            abort(403);
        }
        
        $travelOrderDivisionId = $employeePrimaryPosition->division_id;
        
        // Allow if travel order is from employee in division head's division
        $isFromDivision = $travelOrderDivisionId === $divisionHeadDivisionId;
        
        // Also allow if it's the division head's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        if (!$isFromDivision && !$isOwn) {
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
        
        // Ensure the user is a division head
        if (!$employee->is_divisionhead) {
            abort(403);
        }
        
        // Ensure the division head can only approve travel orders from their division
        $divisionHeadPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $divisionHeadDivisionId = $divisionHeadPrimaryPosition ? $divisionHeadPrimaryPosition->division_id : null;
        
        // Get the employee's primary position division instead of travel order position
        $employeePrimaryPosition = $travelOrder->employee->positions()->where('is_primary', true)->first();
        
        // If no primary position is assigned to the employee, deny access
        if (!$employeePrimaryPosition) {
            abort(403);
        }
        
        $travelOrderDivisionId = $employeePrimaryPosition->division_id;
        
        if ($travelOrderDivisionId !== $divisionHeadDivisionId) {
            abort(403);
        }
        
        // Ensure the travel order is from a regular employee or unit head (but not division head, VP, or president)
        if ($travelOrder->employee && ($travelOrder->employee->is_divisionhead || $travelOrder->employee->is_vp || $travelOrder->employee->is_president)) {
            abort(403);
        }
        
        // For unit head travel orders, no need for head approval
        // For regular employee travel orders, ensure the head has already approved
        if (!$travelOrder->employee->is_head && !$travelOrder->head_approved) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved by division head
        if (!is_null($travelOrder->divisionhead_approved)) {
            abort(403);
        }
        
        // Ensure the travel order is still at the division head approval stage (not yet approved by higher authorities)
        // If VP or President has already approved, this is not the right stage
        if (!is_null($travelOrder->vp_approved) || !is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Approve the travel order
        $travelOrder->update([
            'divisionhead_approved' => true,
            'divisionhead_approved_at' => now(),
            'status' => $travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead ? 'pending' : 'approved', // Keep pending for unit heads (need VP approval), approved for regular employees
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
        
        // Ensure the user is a division head
        if (!$employee->is_divisionhead) {
            abort(403);
        }
        
        // Ensure the division head can only reject travel orders from their division
        $divisionHeadPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $divisionHeadDivisionId = $divisionHeadPrimaryPosition ? $divisionHeadPrimaryPosition->division_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderDivisionId = $travelOrderPosition->division_id;
        
        if ($travelOrderDivisionId !== $divisionHeadDivisionId) {
            abort(403);
        }
        
        // Ensure the travel order is from a regular employee or unit head (but not division head, VP, or president)
        if ($travelOrder->employee && ($travelOrder->employee->is_divisionhead || $travelOrder->employee->is_vp || $travelOrder->employee->is_president)) {
            abort(403);
        }
        
        // For unit head travel orders, no need for head approval
        // For regular employee travel orders, ensure the head has already approved
        if (!$travelOrder->employee->is_head && !$travelOrder->head_approved) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved by division head
        if (!is_null($travelOrder->divisionhead_approved)) {
            abort(403);
        }
        
        // Ensure the travel order is still at the division head approval stage (not yet approved by higher authorities)
        // If VP or President has already approved, this is not the right stage
        if (!is_null($travelOrder->vp_approved) || !is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Reject the travel order
        $travelOrder->update([
            'divisionhead_approved' => false,
            'divisionhead_approved_at' => now(),
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
        
        // Check if the travel order belongs to an employee in the division head's division
        $divisionHeadPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $divisionHeadDivisionId = $divisionHeadPrimaryPosition ? $divisionHeadPrimaryPosition->division_id : null;
        
        // Get the employee's primary position division instead of travel order position
        $employeePrimaryPosition = $travelOrder->employee->positions()->where('is_primary', true)->first();
        
        // If no primary position is assigned to the employee, deny access
        if (!$employeePrimaryPosition) {
            abort(403);
        }
        
        $travelOrderDivisionId = $employeePrimaryPosition->division_id;
        
        // Allow if travel order is from employee in division head's division
        $isFromDivision = $travelOrderDivisionId === $divisionHeadDivisionId;
        
        // Also allow if it's the division head's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        if (!$isFromDivision && !$isOwn) {
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
        
        // Get approver information
        $approverEmployee = $this->getApproverEmployee('UNIT_HEAD', $employee->position);
        $supervisorName = $approverEmployee ? $approverEmployee->first_name . ' ' . $approverEmployee->last_name : 'N/A';
        $supervisorPosition = $approverEmployee && $approverEmployee->position ? $approverEmployee->position->position_name : 'Unit Head';
        
        // Get higher rank officer (VP/President)
        $higherRankOfficer = $this->getApproverEmployee('VP_OR_PRESIDENT', $employee->position);
        $higherRankName = $higherRankOfficer ? $higherRankOfficer->first_name . ' ' . $higherRankOfficer->last_name : 'N/A';
        $higherRankPosition = $higherRankOfficer && $higherRankOfficer->position ? $higherRankOfficer->position->position_name : 'VP/President';
        
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
        
        // Add approval status in cells I17 (Unit Head) and K43 (Division Head/VP/President)
        // I17: Show only when Unit Head has approved/declined
        if ($travelOrder->head_approved_at) {
            $unitHeadStatus = $travelOrder->head_approved ? 'APPROVED' : 'DECLINED';
            $unitHeadTimestamp = $travelOrder->head_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('I17', $unitHeadStatus . ' ' . $unitHeadTimestamp);
        }
        
        // K43: Show Division Head approval status when available
        if ($travelOrder->divisionhead_approved_at) {
            $divisionHeadStatus = $travelOrder->divisionhead_approved ? 'APPROVED' : 'DECLINED';
            $divisionHeadTimestamp = $travelOrder->divisionhead_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('K43', $divisionHeadStatus . ' ' . $divisionHeadTimestamp);
        }
        
        // For VP/President approval, show in K43 if no division head approval
        if ($travelOrder->vp_approved_at && !$travelOrder->divisionhead_approved_at) {
            $vpStatus = $travelOrder->vp_approved ? 'APPROVED' : 'DECLINED';
            $vpTimestamp = $travelOrder->vp_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('K43', $vpStatus . ' ' . $vpTimestamp);
        }
        
        if ($travelOrder->president_approved_at && !$travelOrder->divisionhead_approved_at && !$travelOrder->vp_approved_at) {
            $presidentStatus = $travelOrder->president_approved ? 'APPROVED' : 'DECLINED';
            $presidentTimestamp = $travelOrder->president_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('K43', $presidentStatus . ' ' . $presidentTimestamp);
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
                
            case 'VP_OR_PRESIDENT':
                if ($employeePosition && $employeePosition->office) {
                    $officeName = strtolower($employeePosition->office->office_name);
                    if (strpos($officeName, 'office of the university president') !== false || 
                        strpos($officeName, 'office of the president') !== false) {
                        // President's Office - goes directly to President
                        return \App\Models\Employee::whereHas('officer', function($query) {
                            $query->where('president', true);
                        })->first();
                    } else {
                        // Other offices - goes to VP
                        return \App\Models\Employee::whereHas('officer', function($query) {
                            $query->where('vp', true);
                        })->first();
                    }
                }
                return null;
                
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
        
        // Check if this travel order is from someone in the division head's division (approval context)
        $divisionHeadPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $divisionHeadDivisionId = $divisionHeadPrimaryPosition ? $divisionHeadPrimaryPosition->division_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        $travelOrderDivisionId = $travelOrderPosition ? $travelOrderPosition->division_id : null;
        
        $isFromDivision = $travelOrderDivisionId === $divisionHeadDivisionId;
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        // Allow access if it's from their division or their own travel order
        if (!$isFromDivision && !$isOwn) {
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