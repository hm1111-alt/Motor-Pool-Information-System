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
use ConvertApi\ConvertApi;
use Illuminate\Http\JsonResponse;

class HeadTravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders for head approval with tab support.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Build the query based on the selected tab
        // Find the unit_id of the current employee's primary position
        $primaryPosition = $employee->positions()->where('is_primary', true)->first();
        $unitId = $primaryPosition ? $primaryPosition->unit_id : null;
        
        // Get the current head's employee ID to exclude their own travel orders
        $headEmployeeId = $employee->id;
        
        $query = TravelOrder::whereHas('employee', function ($query) use ($headEmployeeId) {
                $query->where('id', '!=', $headEmployeeId) // Exclude the head's own travel orders
                ->whereDoesntHave('officer', function ($officerQuery) {
                    $officerQuery->where('division_head', true);
                })
                ->whereDoesntHave('officer', function ($officerQuery) {
                    $officerQuery->where('vp', true);
                })
                ->whereDoesntHave('officer', function ($officerQuery) {
                    $officerQuery->where('president', true);
                });
            })
            ->whereHas('position', function ($positionQuery) use ($unitId) {
                $positionQuery->where('unit_id', $unitId);
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
                $query->where('head_approved', true);
                break;
            case 'cancelled':
                $query->where('head_approved', false);
                break;
            case 'pending':
            default:
                $query->where('head_approved', null);
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
        
        return view('travel-orders.approvals.head-index', compact('travelOrders', 'tab', 'search'));
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
        
        // Check if the travel order belongs to an employee in the head's unit
        $headPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $headUnitId = $headPrimaryPosition ? $headPrimaryPosition->unit_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderUnitId = $travelOrderPosition->unit_id;
        
        // Allow if travel order is from employee in head's unit
        $isFromUnit = $travelOrderUnitId === $headUnitId;
        
        // Also allow if it's the head's own travel order (though they shouldn't typically approve their own)
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        if (!$isFromUnit && !$isOwn) {
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
        
        // Ensure the head can only approve travel orders from their unit
        $headPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $headUnitId = $headPrimaryPosition ? $headPrimaryPosition->unit_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderUnitId = $travelOrderPosition->unit_id;
        
        if ($travelOrderUnitId !== $headUnitId) {
            abort(403);
        }
        
        // Ensure the head cannot approve their own travel order
        if ($travelOrder->employee_id === $employee->id) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved
        if ($travelOrder->head_approved) {
            abort(403);
        }
        
        // Ensure the travel order is still at the head approval stage (not yet approved by higher authorities)
        // If division head or VP or President has already approved, this is not the right stage
        if (!is_null($travelOrder->divisionhead_approved) || 
            !is_null($travelOrder->vp_approved) || 
            !is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Approve the travel order
        $travelOrder->update([
            'head_approved' => true,
            'head_approved_at' => now(),
            'status' => 'pending', // Still pending division head approval
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
        
        // Ensure the head can only reject travel orders from their unit
        $headPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $headUnitId = $headPrimaryPosition ? $headPrimaryPosition->unit_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderUnitId = $travelOrderPosition->unit_id;
        
        if ($travelOrderUnitId !== $headUnitId) {
            abort(403);
        }
        
        // Ensure the head cannot reject their own travel order
        if ($travelOrder->employee_id === $employee->id) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved
        if ($travelOrder->head_approved) {
            abort(403);
        }
        
        // Ensure the travel order is still at the head approval stage (not yet approved by higher authorities)
        // If division head or VP or President has already approved, this is not the right stage
        if (!is_null($travelOrder->divisionhead_approved) || 
            !is_null($travelOrder->vp_approved) || 
            !is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Reject the travel order
        $travelOrder->update([
            'head_approved' => false,
            'head_approved_at' => now(),
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
        
        // Check if the travel order belongs to an employee in the head's unit
        $headPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $headUnitId = $headPrimaryPosition ? $headPrimaryPosition->unit_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderUnitId = $travelOrderPosition->unit_id;
        
        // Allow if travel order is from employee in head's unit
        $isFromUnit = $travelOrderUnitId === $headUnitId;
        
        // Also allow if it's the head's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        if (!$isFromUnit && !$isOwn) {
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
        $approverEmployee = $this->getApproverEmployee('DIVISION_HEAD', $employee->position);
        $supervisorName = $approverEmployee ? $approverEmployee->first_name . ' ' . $approverEmployee->last_name : 'N/A';
        $supervisorPosition = $approverEmployee && $approverEmployee->position ? $approverEmployee->position->position_name : 'Division Head';
        
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
        
        // Add approval status in cells H17 (Division Head/Unit Head) and I43 (VP/President)
        // H17: Show Unit Head approval status when available (since unit head is approving this travel order)
        if ($travelOrder->head_approved_at) {
            $unitHeadStatus = $travelOrder->head_approved ? 'APPROVED' : 'DECLINED';
            $unitHeadTimestamp = $travelOrder->head_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('H17', $unitHeadStatus . ' ' . $unitHeadTimestamp);
        }
        
        // I43: Show VP approval status when available
        if ($travelOrder->vp_approved_at) {
            $vpStatus = $travelOrder->vp_approved ? 'APPROVED' : 'DECLINED';
            $vpTimestamp = $travelOrder->vp_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('I43', $vpStatus . ' ' . $vpTimestamp);
        }
        
        // For President approval, show in I43 if no VP approval
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
        
        // Check if this travel order is from someone in the unit head's unit (approval context)
        $headPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $headUnitId = $headPrimaryPosition ? $headPrimaryPosition->unit_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        $travelOrderUnitId = $travelOrderPosition ? $travelOrderPosition->unit_id : null;
        
        $isFromUnit = $travelOrderUnitId === $headUnitId;
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        // Allow access if it's from their unit or their own travel order
        if (!$isFromUnit && !$isOwn) {
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