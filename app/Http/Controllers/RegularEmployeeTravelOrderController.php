<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ConvertApi\ConvertApi;

class RegularEmployeeTravelOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if employee exists
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an employee record. Please contact your administrator to set up your employee profile.');
        }
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Get travel orders for this employee based on the selected tab
        $query = TravelOrder::where('employee_id', $employee->id);
        
        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('destination', 'LIKE', "%{$search}%")
                  ->orWhere('purpose', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%")
                  ->orWhere('remarks', 'LIKE', "%{$search}%");
            });
        }
        
        switch ($tab) {
            case 'approved':
                $query->where('status', 'approved');
                break;
            case 'cancelled':
                $query->where('status', 'cancelled');
                break;
            case 'pending':
            default:
                $query->where('status', 'pending');
                break;
        }
        
        // Get paginated results with position information
        $travelOrders = $query->with('position')->orderBy('created_at', 'desc')->paginate(10)->appends($request->except('page'));
        
        // Check if this is an AJAX request for partial updates
        if ($request->ajax() || $request->get('ajax')) {
            return response()->json([
                'table_body' => view('travel-orders.partials.table-rows', compact('travelOrders', 'tab'))->with('travelOrders', $travelOrders->load('position'))->render(),
                'pagination' => (string) $travelOrders->withQueryString()->links()
            ]);
        }
        
        return view('travel-orders.index', compact('travelOrders', 'tab', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        if (!$employee) {
            abort(403, 'You do not have an employee record.');
        }
        
        // Get all positions for the employee
        $positions = $employee->positions()->with(['office', 'division', 'unit', 'subunit', 'class'])->get();
        
        return view('travel-orders.create', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'destination' => 'required|string|max:255',
            'date_from' => 'required|date|before_or_equal:date_to',
            'date_to' => 'required|date|after_or_equal:date_from',
            'departure_time' => 'nullable|string|max:20', // Increased max length to accommodate any format
            'purpose' => 'required|string|max:500',
            'position_id' => 'required|exists:emp_positions,id', // Add validation for position selection
        ]);

        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if employee exists
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an employee record. Please contact your administrator to set up your employee profile.');
        }

        // Verify that the selected position belongs to the employee
        $position = $employee->positions()->where('id', $request->position_id)->first();
        if (!$position) {
            return redirect()->back()
                ->with('error', 'Invalid position selected.')
                ->withInput();
        }

        // Process departure_time to ensure it's in the correct format or null
        $departureTime = null;
        if (!empty($request->departure_time)) {
            // Trim whitespace and normalize the value
            $cleanTime = trim($request->departure_time);
            
            // Try to extract time in H:i format using regex
            if (preg_match('/([01]?[0-9]|2[0-3]):([0-5][0-9])/', $cleanTime, $matches)) {
                $departureTime = $matches[1] . ':' . $matches[2];
            } elseif (preg_match('/([01]?[0-9]|2[0-3])[.:]?([0-5][0-9])/', $cleanTime, $matches)) {
                // Handle cases where it might be formatted differently
                $departureTime = sprintf('%02d:%02d', $matches[1], $matches[2]);
            }
        }

        // Create the travel order
        $travelOrder = TravelOrder::create([
            'employee_id' => $employee->id,
            'emp_position_id' => $request->position_id, // Associate with the selected position
            'destination' => $request->destination,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'departure_time' => $departureTime,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        return redirect()->route('travel-orders.index')
            ->with('success', 'Travel order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TravelOrder $travelOrder)
    {
        // Ensure the employee can only view their own travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if employee exists
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an employee record. Please contact your administrator to set up your employee profile.');
        }
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        return view('travel-orders.show', compact('travelOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TravelOrder $travelOrder)
    {
        // Ensure the employee can only edit their own pending travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if employee exists
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an employee record. Please contact your administrator to set up your employee profile.');
        }
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow editing if not yet approved
        if ($travelOrder->head_approved || $travelOrder->vp_approved) {
            abort(403);
        }
        
        return view('travel-orders.edit', compact('travelOrder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the employee can only update their own pending travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if employee exists
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an employee record. Please contact your administrator to set up your employee profile.');
        }
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow updating if not yet approved
        if ($travelOrder->head_approved || $travelOrder->vp_approved) {
            abort(403);
        }
        
        $request->validate([
            'destination' => 'required|string|max:255',
            'date_from' => 'required|date|before_or_equal:date_to',
            'date_to' => 'required|date|after_or_equal:date_from',
            'departure_time' => 'nullable|string|max:20', // Increased max length to accommodate any format
            'purpose' => 'required|string|max:500',
        ]);

        // Process departure_time to ensure it's in the correct format or null
        $departureTime = null;
        if (!empty($request->departure_time)) {
            // Trim whitespace and normalize the value
            $cleanTime = trim($request->departure_time);
            
            // Try to extract time in H:i format using regex
            if (preg_match('/([01]?[0-9]|2[0-3]):([0-5][0-9])/', $cleanTime, $matches)) {
                $departureTime = $matches[1] . ':' . $matches[2];
            } elseif (preg_match('/([01]?[0-9]|2[0-3])[.:]?([0-5][0-9])/', $cleanTime, $matches)) {
                // Handle cases where it might be formatted differently
                $departureTime = sprintf('%02d:%02d', $matches[1], $matches[2]);
            }
        }

        $travelOrder->update([
            'destination' => $request->destination,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'departure_time' => $departureTime,
            'purpose' => $request->purpose,
        ]);

        return redirect()->route('travel-orders.index')
            ->with('success', 'Travel order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the employee can only delete their own pending travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if employee exists
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an employee record. Please contact your administrator to set up your employee profile.');
        }
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow deleting if not yet approved
        if ($travelOrder->head_approved || $travelOrder->vp_approved) {
            abort(403);
        }
        
        $travelOrder->delete();

        return redirect()->route('travel-orders.index')
            ->with('success', 'Travel order deleted successfully.');
    }
    
    /**
     * Get the creator of a travel order.
     */
    public function getCreator($id): \Illuminate\Http\JsonResponse
    {
        $travelOrder = TravelOrder::with('employee')->find($id);
        
        if (!$travelOrder) {
            return response()->json(['error' => 'Travel order not found'], 404);
        }
        
        $creatorName = '';
        if ($travelOrder->employee) {
            $creatorName = $travelOrder->employee->first_name . ' ' . $travelOrder->employee->last_name;
        }
        
        return response()->json([
            'creator_name' => $creatorName
        ]);
    }
    
    /**
     * Generate PDF for travel order (exact copy of Motorpool Admin with employee authentication)
     */
    public function generatePDF(TravelOrder $travelOrder): Response
    {
        // Ensure the employee can only view their own travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if employee exists
        if (!$employee) {
            abort(403);
        }
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Load the travel order with all necessary relationships
        $travelOrder->load([
            'employee', 
            'employee.user',
            'position',  // Load the travel order's position (not employee's position)
            'employee.office',
            'employee.division',
            'employee.unit'
        ]);
        
        // Get employee information
        $employee = $travelOrder->employee;
        $fullName = $employee->first_name . ' ' . $employee->last_name;
        $positionName = $travelOrder->position ? $travelOrder->position->position_name : $employee->position_name;
        
        // Determine organizational unit name
        $orgUnitName = '';
        if ($employee->unit) {
            $orgUnitName = $employee->unit->unit_name;
        } elseif ($employee->division) {
            $orgUnitName = $employee->division->division_name;
        } elseif ($employee->office) {
            $orgUnitName = $employee->office->office_name;
        }
        
        // Get supervisor information
        $supervisorInfo = $this->getSupervisorInfo($employee);
        
        // Load the Excel template
        $templatePath = public_path('templates/travel_order_template.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        // Fill the Excel cells with data (EXACT COPY FROM MOTORPOOL ADMIN)
        $sheet->setCellValue('C9', $fullName);  // Employee name  
        $sheet->setCellValue('C10', $travelOrder->purpose);  // Purpose
        $sheet->setCellValue('G11', $travelOrder->destination);  // Destination
        $sheet->setCellValue('E23', $fullName);  // Employee name (again)
        $sheet->setCellValue('E24', $positionName);  // Position
        $sheet->setCellValue('E25', $orgUnitName);  // Unit/Division/Office
        $sheet->setCellValue('E26', $travelOrder->purpose);  // Purpose
        $sheet->setCellValue('B33', $travelOrder->date_from ? $travelOrder->date_from->format('F j, Y') : '');  // Date From
        $sheet->setCellValue('B35', $travelOrder->date_to ? $travelOrder->date_to->format('F j, Y') : '');  // Date To
        $sheet->setCellValue('D34', $travelOrder->destination);  // Destination (again)
        $sheet->setCellValue('G34', $travelOrder->departure_time ?? '');  // Departure Time
        $sheet->setCellValue('H34', '');  // Arrival Time (not in model)
        
        // Get employee role and approval workflow
        $employeeRole = $this->getEmployeeRole($employee);
        $workflow = $this->getApprovalWorkflow($employee);
        
        // Initialize officers
        $higherRankOfficer = ['name' => '', 'position' => ''];
        $lowerRankOfficer = ['name' => '', 'position' => ''];
        
        // Get the position associated with this travel order
        $travelOrderPosition = $travelOrder->position;
        
        // For regular employees: H19/H20 = Division Head, I45/I46 = Unit Head
        // Always show both approvers regardless of approval status
        
        // Get Division Head (H19/H20)
        $divisionHead = $this->getApproverEmployee('DIVISION_HEAD', $travelOrderPosition);
        if ($divisionHead) {
            $higherRankOfficer['name'] = $divisionHead->first_name . ' ' . $divisionHead->last_name;
            $higherRankOfficer['position'] = $divisionHead->position ? $divisionHead->position->position_name : 'Division Head';
        }
        
        // Get Unit Head (I45/I46)
        $unitHead = $this->getApproverEmployee('UNIT_HEAD', $travelOrderPosition);
        if ($unitHead) {
            $lowerRankOfficer['name'] = $unitHead->first_name . ' ' . $unitHead->last_name;
            $lowerRankOfficer['position'] = $unitHead->position ? $unitHead->position->position_name : 'Unit Head';
        }
        
        // Fallback to original supervisor info if needed
        if (empty($higherRankOfficer['name']) && empty($lowerRankOfficer['name'])) {
            // Fallback to original supervisor info if no position found
            $higherRankOfficer = $supervisorInfo;
            $lowerRankOfficer = $supervisorInfo;
        }
        
        // Set the officers in the correct cells
        // Higher rank officer in H19 (approving office) and H20 (position)
        $sheet->setCellValue('H19', $higherRankOfficer['name'] ?? 'N/A');  // Higher rank approving officer name
        $sheet->setCellValue('H20', $higherRankOfficer['position'] ?? 'N/A');  // Higher rank approving officer position
        
        // Lower rank officer in I45 (approving office) and I46 (position)
        $sheet->setCellValue('I45', $lowerRankOfficer['name'] ?? 'N/A');  // Lower rank approving officer name
        $sheet->setCellValue('I46', $lowerRankOfficer['position'] ?? 'N/A');  // Lower rank approving officer position
        
        $sheet->setCellValue('I41', $fullName);  // Employee name (signatory)
        $sheet->setCellValue('I42', $positionName);  // Employee position (signatory)
        
        // Add approval status in cells I17 (Division Head) and K43 (Unit Head)
        // I17: Show only when Division Head has approved/declined
        // K43: Show only when Unit Head has approved/declined
        
        // For Division Head approval status (I17)
        if ($travelOrder->divisionhead_approved_at) {
            $divisionHeadStatus = $travelOrder->divisionhead_approved ? 'APPROVED' : 'DECLINED';
            $divisionHeadTimestamp = $travelOrder->divisionhead_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('I17', $divisionHeadStatus . ' ' . $divisionHeadTimestamp);
        }
        
        // For Unit Head approval status (K43)
        if ($travelOrder->head_approved_at) {
            $unitHeadStatus = $travelOrder->head_approved ? 'APPROVED' : 'DECLINED';
            $unitHeadTimestamp = $travelOrder->head_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('K43', $unitHeadStatus . ' ' . $unitHeadTimestamp);
        }
        
        // Save Excel temporarily for conversion (EXACT COPY FROM MOTORPOOL ADMIN)
        $excelTemp = tempnam(sys_get_temp_dir(), 'travel_order_') . '.xlsx';
        IOFactory::createWriter($spreadsheet, 'Xlsx')->save($excelTemp);
        
        // Check if ConvertAPI is configured (EXACT COPY FROM MOTORPOOL ADMIN)
        $convertApiSecret = config('services.convertapi.secret');
        
        if (!empty($convertApiSecret)) {
            try {
                // Use ConvertAPI to convert Excel to PDF (EXACT COPY FROM MOTORPOOL ADMIN)
                ConvertApi::setApiCredentials($convertApiSecret);
                $result = ConvertApi::convert('pdf', ['File' => $excelTemp], 'xlsx');
                $pdfPath = tempnam(sys_get_temp_dir(), 'travel_order_pdf_') . '.pdf';
                $result->getFile()->save($pdfPath);
                
                // Read the generated PDF content
                $pdfContent = file_get_contents($pdfPath);
                
                // Clean up temporary files
                unlink($excelTemp);
                unlink($pdfPath);
            } catch (\Exception $e) {
                // Clean up the Excel temp file in case of error
                if (file_exists($excelTemp)) {
                    unlink($excelTemp);
                }
                
                // Fallback to MPDF when ConvertAPI fails
                try {
                    $tempPdfPath = storage_path('app/temp_travel_order_' . $travelOrder->id . '.pdf');
                    
                    // Use the MPDF library directly with PhpSpreadsheet
                    $pdfWriter = IOFactory::createWriter($spreadsheet, 'Mpdf');
                    $pdfWriter->save($tempPdfPath);
                    
                    // Read the generated PDF content
                    $pdfContent = file_get_contents($tempPdfPath);
                    
                    // Clean up temporary file
                    unlink($tempPdfPath);
                } catch (\Exception $mpdfException) {
                    // If both ConvertAPI and MPDF fail, return error PDF
                    $errorContent = 'PDF Generation Error: Both ConvertAPI and MPDF failed. ' . $mpdfException->getMessage();
                    return response($errorContent, 500)
                        ->header('Content-Type', 'text/plain');
                }
            }
        } else {
            // Fallback to MPDF if ConvertAPI is not configured (EXACT COPY FROM MOTORPOOL ADMIN)
            try {
                $tempPdfPath = storage_path('app/temp_travel_order_' . $travelOrder->id . '.pdf');
                
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
                // Clean up the Excel temp file in case of error
                if (file_exists($excelTemp)) {
                    unlink($excelTemp);
                }
                
                // Return error PDF
                $errorContent = 'MPDF Generation Error: ' . $e->getMessage();
                return response($errorContent, 500)
                    ->header('Content-Type', 'text/plain');
            }
        }
        
        // Return PDF response (EXACT COPY FROM MOTORPOOL ADMIN)
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="travel_order_' . $travelOrder->id . '.pdf"');
    }
    
    /**
     * Get employee role (Regular, Unit Head, Division Head, VP, President)
     */
    private function getEmployeeRole($employee)
    {
        if ($employee->is_president) {
            return 'PRESIDENT';
        }
        if ($employee->is_vp) {
            return 'VP';
        }
        if ($employee->is_divisionhead) {
            return 'DIVISION_HEAD';
        }
        if ($employee->is_head) {
            return 'UNIT_HEAD';
        }
        return 'REGULAR';
    }
    
    /**
     * Get approval workflow for an employee based on their role
     */
    private function getApprovalWorkflow($employee)
    {
        $role = $this->getEmployeeRole($employee);
        
        switch ($role) {
            case 'REGULAR':
                // Regular employee: approved by Unit Head & Division Head
                return [
                    'first_approver' => 'UNIT_HEAD',
                    'second_approver' => 'DIVISION_HEAD'
                ];
                
            case 'UNIT_HEAD':
                // Unit Head: approved by Division Head and VP (President if no VP)
                return [
                    'first_approver' => 'DIVISION_HEAD',
                    'second_approver' => 'VP_OR_PRESIDENT'
                ];
                
            case 'DIVISION_HEAD':
                // Division Head: approved by VP & President (if no VP then only President)
                return [
                    'first_approver' => 'VP',
                    'second_approver' => 'PRESIDENT'
                ];
                
            case 'VP':
                // VP: approved by President
                return [
                    'first_approver' => 'PRESIDENT',
                    'second_approver' => null
                ];
                
            case 'PRESIDENT':
                // President: no approval needed
                return [
                    'first_approver' => null,
                    'second_approver' => null
                ];
                
            default:
                return [
                    'first_approver' => null,
                    'second_approver' => null
                ];
        }
    }
    
    /**
     * Get the actual approver employee based on role and position
     */
    private function getApproverEmployee($approverRole, $employeePosition)
    {
        switch ($approverRole) {
            case 'UNIT_HEAD':
                // Find Unit Head in the same unit (excluding the requester)
                return \App\Models\Employee::whereHas('positions', function($query) use ($employeePosition) {
                    $query->where('is_unit_head', true)
                          ->where('unit_id', $employeePosition->unit_id)
                          ->where('employee_id', '!=', $employeePosition->employee_id);
                })->first();
                
            case 'DIVISION_HEAD':
                // Find Division Head in the same division
                return \App\Models\Employee::whereHas('positions', function($query) use ($employeePosition) {
                    $query->where('is_division_head', true)
                          ->where('division_id', $employeePosition->division_id);
                })->first();
                
            case 'VP':
                // Find VP (check if President's Office first)
                if ($employeePosition && $employeePosition->office) {
                    if (stripos($employeePosition->office->office_name, 'President') !== false) {
                        // President's Office - no VP, return null
                        return null;
                    }
                }
                // Find any VP
                return \App\Models\Employee::whereHas('officer', function($query) {
                    $query->where('vp', true);
                })->first();
                
            case 'PRESIDENT':
                // Find President
                return \App\Models\Employee::whereHas('officer', function($query) {
                    $query->where('president', true);
                })->first();
                
            case 'VP_OR_PRESIDENT':
                // Special case: VP if not President's Office, otherwise President
                if ($employeePosition && $employeePosition->office) {
                    if (stripos($employeePosition->office->office_name, 'President') !== false) {
                        // President's Office - go directly to President
                        return \App\Models\Employee::whereHas('officer', function($query) {
                            $query->where('president', true);
                        })->first();
                    }
                }
                // Regular office - find VP
                return \App\Models\Employee::whereHas('officer', function($query) {
                    $query->where('vp', true);
                })->first();
                
            default:
                return null;
        }
    }
    
    /**
     * Get supervisor information based on employee's position
     */
    private function getSupervisorInfo($employee)
    {
        $name = '';
        $position = '';
        
        // If employee is President - no supervisor
        if ($employee->is_president) {
            return ['name' => 'N/A', 'position' => 'N/A'];
        }
        
        // If employee is VP - supervisor is President
        if ($employee->is_vp) {
            $president = \App\Models\Employee::whereHas('positions', function($query) {
                $query->where('is_president', true);
            })->orWhereHas('officer', function($query) {
                $query->where('president', true);
            })->first();
            if ($president) {
                $name = $president->first_name . ' ' . $president->last_name;
                $position = $president->position ? $president->position->position_name : 'President';
            }
            return ['name' => $name, 'position' => $position];
        }
        
        // If employee is Division Head - supervisor is VP
        if ($employee->is_divisionhead) {
            $vp = \App\Models\Employee::whereHas('positions', function($query) {
                $query->where('is_vp', true);
            })->orWhereHas('officer', function($query) {
                $query->where('vp', true);
            })->first();
            if ($vp) {
                $name = $vp->first_name . ' ' . $vp->last_name;
                $position = $vp->position ? $vp->position->position_name : 'Vice President';
            }
            return ['name' => $name, 'position' => $position];
        }
        
        // If employee is Unit Head - supervisor is Division Head
        if ($employee->is_head) {
            // Try to find division head in the same division
            $divisionHead = \App\Models\Employee::whereHas('positions', function($query) {
                $query->where('is_division_head', true);
            })
            ->orWhereHas('officer', function($query) {
                $query->where('division_head', true);
            })
                ->whereHas('positions', function($query) use ($employee) {
                    $query->where('division_id', $employee->getDivisionIdAttribute());
                })->first();
            
            if ($divisionHead) {
                $name = $divisionHead->first_name . ' ' . $divisionHead->last_name;
                $position = $divisionHead->position ? $divisionHead->position->position_name : 'Division Head';
            } else {
                // Fallback to any division head
                $divisionHead = \App\Models\Employee::whereHas('positions', function($query) {
                    $query->where('is_division_head', true);
                })->orWhereHas('officer', function($query) {
                    $query->where('division_head', true);
                })->first();
                if ($divisionHead) {
                    $name = $divisionHead->first_name . ' ' . $divisionHead->last_name;
                    $position = $divisionHead->position ? $divisionHead->position->position_name : 'Division Head';
                }
            }
            return ['name' => $name, 'position' => $position];
        }
        
        // For regular employees - supervisor is Unit Head
        $unitHead = \App\Models\Employee::whereHas('positions', function($query) {
                $query->where('is_unit_head', true);
            })
            ->orWhereHas('officer', function($query) {
                $query->where('unit_head', true);
            })
            ->whereHas('positions', function($query) use ($employee) {
                $query->where('unit_id', $employee->getUnitIdAttribute());
            })->first();
        
        if ($unitHead) {
            $name = $unitHead->first_name . ' ' . $unitHead->last_name;
            $position = $unitHead->position ? $unitHead->position->position_name : 'Unit Head';
        } else {
            // Fallback to any unit head
            $unitHead = \App\Models\Employee::whereHas('positions', function($query) {
                $query->where('is_unit_head', true);
            })->orWhereHas('officer', function($query) {
                $query->where('unit_head', true);
            })->first();
            if ($unitHead) {
                $name = $unitHead->first_name . ' ' . $unitHead->last_name;
                $position = $unitHead->position ? $unitHead->position->position_name : 'Unit Head';
            }
        }
        
        return ['name' => $name, 'position' => $position];
    }

    /**
     * Get travel order details for modal display
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
        
        // Ensure the travel order belongs to this employee
        if ($travelOrder->employee_id !== $employee->id) {
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