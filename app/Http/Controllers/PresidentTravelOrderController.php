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

class PresidentTravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders for president approval with tab support.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Build the query to include all travel orders that could be relevant to the president
        // This includes both VP and division head travel orders regardless of approval status
        $query = TravelOrder::where(function ($subQuery) {
                // Include VP travel orders (they go directly to president)
                $subQuery->whereHas('employee.officer', function ($officerQuery) {
                    $officerQuery->where('vp', true);
                });
                
                // Or include division head travel orders that need president approval
                $subQuery->orWhere(function ($orSubQuery) {
                    $orSubQuery->whereHas('employee.positions', function ($positionQuery) {
                        $positionQuery->where('is_division_head', true)
                              ->where('is_vp', false)
                              ->where('is_president', false);
                    })
                    ->where('vp_approved', true); // Already approved by VP, need president approval
                })
                // Or include division head travel orders from President's Office (no VP)
                ->orWhere(function ($orSubQuery) {
                    $orSubQuery->whereHas('employee.positions', function ($positionQuery) {
                        $positionQuery->where('is_division_head', true)
                              ->whereHas('office', function ($officeQuery) {
                                  $officeQuery->where('office_name', 'Office of the University President');
                              });
                    })
                    ->whereNull('president_approved'); // Pending President approval
                });
                
                // Or include unit head travel orders that have been approved by division head
                // when there's no VP in the same office
                $subQuery->orWhere(function ($orQuery) {
                    $orQuery->whereHas('employee.officer', function ($officerQuery) {
                        $officerQuery->where('unit_head', true)
                              ->where('division_head', false)
                              ->where('vp', false)
                              ->where('president', false);
                    })
                    ->where('divisionhead_approved', true) // Approved by division head
                    ->whereNull('vp_approved') // Not yet approved by VP (because no VP in office)
                    ->whereNull('president_approved'); // Not yet approved by President
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
                $query->where(function($q) {
                    $q->where('president_approved', true);
                });
                break;
            case 'cancelled':
                $query->where(function($q) {
                    $q->where('president_approved', false);
                });
                break;
            case 'pending':
            default:
                $query->where(function($q) {
                    $q->where('president_approved', null);
                });
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
        
        return view('travel-orders.approvals.president-index', compact('travelOrders', 'tab', 'search'));
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
        
        // Check if the user is a president
        if (!$employee->is_president) {
            abort(403);
        }
        
        // Also allow if it's the president's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        // For presidents, allow viewing any travel order that requires presidential approval
        // This includes VP travel orders, division head travel orders, and unit head travel orders
        $requiresPresApproval = false;
        
        if ($travelOrder->employee && $travelOrder->employee->is_vp && is_null($travelOrder->president_approved)) {
            $requiresPresApproval = true;
        } elseif ($travelOrder->employee && $travelOrder->employee->is_divisionhead && $travelOrder->vp_approved && is_null($travelOrder->president_approved)) {
            $requiresPresApproval = true;
        } elseif ($travelOrder->employee && $travelOrder->employee->is_divisionhead && 
                  is_null($travelOrder->president_approved) &&
                  $travelOrder->employee->positions()->where('is_division_head', true)
                      ->whereHas('office', function ($officeQuery) {
                          $officeQuery->where('office_name', 'like', '%President%');
                      })->exists()) {
            // Division head from President's Office (no VP needed)
            $requiresPresApproval = true;
        } elseif ($travelOrder->employee && $travelOrder->employee->is_head && 
                  !$travelOrder->employee->is_divisionhead && 
                  !$travelOrder->employee->is_vp && 
                  $travelOrder->divisionhead_approved && 
                  is_null($travelOrder->vp_approved) && 
                  is_null($travelOrder->president_approved)) {
            $requiresPresApproval = true;
        }
        
        if (!$requiresPresApproval && !$isOwn) {
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
        
        // Ensure the travel order is valid for presidential approval
        $isValidTravelOrder = false;
        
        // Check if it's a VP travel order
        if ($travelOrder->employee && $travelOrder->employee->is_vp && is_null($travelOrder->president_approved)) {
            $isValidTravelOrder = true;
        } 
        // Check if it's a division head travel order approved by VP
        elseif ($travelOrder->employee && $travelOrder->employee->is_divisionhead && $travelOrder->vp_approved && is_null($travelOrder->president_approved)) {
            $isValidTravelOrder = true;
        }
        // Check if it's a division head travel order from President's Office (no VP needed)
        elseif ($travelOrder->employee && $travelOrder->employee->is_divisionhead && 
                is_null($travelOrder->president_approved) &&
                $travelOrder->employee->positions()->where('is_division_head', true)
                    ->whereHas('office', function ($officeQuery) {
                        $officeQuery->where('office_name', 'like', '%President%');
                    })->exists()) {
            $isValidTravelOrder = true;
        }
        // Check if it's a unit head travel order approved by division head (when no VP in office)
        elseif ($travelOrder->employee && $travelOrder->employee->is_head && 
                !$travelOrder->employee->is_divisionhead && 
                !$travelOrder->employee->is_vp && 
                $travelOrder->divisionhead_approved && 
                is_null($travelOrder->vp_approved) && 
                is_null($travelOrder->president_approved)) {
            $isValidTravelOrder = true;
        }
        
        if (!$isValidTravelOrder) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved by president
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Approve the travel order
        $travelOrder->update([
            'president_approved' => true,
            'president_approved_at' => now(),
            'status' => 'approved',
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
        
        // Ensure the travel order is from a VP or division head
        $isValidTravelOrder = false;
        
        // Check if it's a VP travel order
        if ($travelOrder->employee && $travelOrder->employee->is_vp && is_null($travelOrder->president_approved)) {
            $isValidTravelOrder = true;
        } 
        // Check if it's a division head travel order
        elseif ($travelOrder->employee && $travelOrder->employee->is_divisionhead && $travelOrder->vp_approved && is_null($travelOrder->president_approved)) {
            $isValidTravelOrder = true;
        }
        
        if (!$isValidTravelOrder) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved by president
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Reject the travel order
        $travelOrder->update([
            'president_approved' => false,
            'president_approved_at' => now(),
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
        
        // Check if the user is a president
        if (!$employee->is_president) {
            abort(403);
        }
        
        // Also allow if it's the president's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        // For presidents, allow viewing any travel order that requires presidential approval
        // This includes VP travel orders, division head travel orders, and unit head travel orders
        $requiresPresApproval = false;
        
        if ($travelOrder->employee && $travelOrder->employee->is_vp && is_null($travelOrder->president_approved)) {
            $requiresPresApproval = true;
        } elseif ($travelOrder->employee && $travelOrder->employee->is_divisionhead && $travelOrder->vp_approved && is_null($travelOrder->president_approved)) {
            $requiresPresApproval = true;
        } elseif ($travelOrder->employee && $travelOrder->employee->is_divisionhead && 
                  is_null($travelOrder->president_approved) &&
                  $travelOrder->employee->positions()->where('is_division_head', true)
                      ->whereHas('office', function ($officeQuery) {
                          $officeQuery->where('office_name', 'like', '%President%');
                      })->exists()) {
            // Division head from President's Office (no VP needed)
            $requiresPresApproval = true;
        } elseif ($travelOrder->employee && $travelOrder->employee->is_head && 
                  !$travelOrder->employee->is_divisionhead && 
                  !$travelOrder->employee->is_vp && 
                  $travelOrder->divisionhead_approved && 
                  is_null($travelOrder->vp_approved) && 
                  is_null($travelOrder->president_approved)) {
            $requiresPresApproval = true;
        }
        
        if (!$requiresPresApproval && !$isOwn) {
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
        } elseif ($employee->is_divisionhead && !$employee->is_vp) {
            // Division head - approver is VP (unless President's Office)
            $approverEmployee = $this->getApproverEmployee('VP', $employee->position);
            $supervisorName = $approverEmployee ? $approverEmployee->first_name . ' ' . $approverEmployee->last_name : 'N/A';
            $supervisorPosition = $approverEmployee && $approverEmployee->position ? $approverEmployee->position->position_name : 'VP';
        } elseif ($employee->is_vp) {
            // VP - no immediate supervisor, goes directly to President
            $supervisorName = '';
            $supervisorPosition = '';
        }
        
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
        $sheet->setCellValue('I45', 'University President');
        $sheet->setCellValue('I46', 'University President');
        
        // Add approval status in cells I17 (Division Head/Unit Head/VP) and K43 (President)
        // I17: Show previous approval status
        if ($travelOrder->divisionhead_approved_at) {
            $divisionHeadStatus = $travelOrder->divisionhead_approved ? 'APPROVED' : 'DECLINED';
            $divisionHeadTimestamp = $travelOrder->divisionhead_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('I17', $divisionHeadStatus . ' ' . $divisionHeadTimestamp);
        } elseif ($travelOrder->head_approved_at) {
            $unitHeadStatus = $travelOrder->head_approved ? 'APPROVED' : 'DECLINED';
            $unitHeadTimestamp = $travelOrder->head_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('I17', $unitHeadStatus . ' ' . $unitHeadTimestamp);
        } elseif ($travelOrder->vp_approved_at) {
            $vpStatus = $travelOrder->vp_approved ? 'APPROVED' : 'DECLINED';
            $vpTimestamp = $travelOrder->vp_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('I17', $vpStatus . ' ' . $vpTimestamp);
        }
        
        // K43: Show President approval status when available
        if ($travelOrder->president_approved_at) {
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
                
            case 'VP':
                return \App\Models\Employee::whereHas('officer', function($query) {
                    $query->where('vp', true);
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
        
        // Check if the user is a president
        if (!$employee->is_president) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access - not a president'
            ], 403);
        }
        
        // Also allow if it's the president's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        // For presidents, allow viewing any travel order that requires presidential approval
        // This includes VP travel orders, division head travel orders, and unit head travel orders
        $requiresPresApproval = false;
        
        if ($travelOrder->employee && $travelOrder->employee->is_vp && is_null($travelOrder->president_approved)) {
            $requiresPresApproval = true;
        } elseif ($travelOrder->employee && $travelOrder->employee->is_divisionhead && $travelOrder->vp_approved && is_null($travelOrder->president_approved)) {
            $requiresPresApproval = true;
        } elseif ($travelOrder->employee && $travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead && $travelOrder->divisionhead_approved && is_null($travelOrder->president_approved)) {
            $requiresPresApproval = true;
        }
        
        // Allow access if it's their own travel order or requires their approval
        if (!$isOwn && !$requiresPresApproval) {
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