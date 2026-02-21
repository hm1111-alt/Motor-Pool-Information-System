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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use ConvertApi\ConvertApi;

class UnitHeadOwnTravelOrderController extends Controller
{
    /**
     * Display a listing of the unit head's own travel orders.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get travel orders for this employee based on the selected tab
        $query = TravelOrder::where('employee_id', $employee->id);
        
        switch ($tab) {
            case 'approved':
                // For unit heads, approved means both division head and VP have approved
                $query->where('divisionhead_approved', true)->where('vp_approved', true);
                break;
            case 'cancelled':
                // For unit heads, cancelled means either division head or VP rejected
                $query->where(function ($q) {
                    $q->where('divisionhead_approved', false)
                      ->orWhere('vp_approved', false);
                });
                break;
            case 'pending':
            default:
                // For unit heads, pending means either:
                // 1. Not yet approved by division head, or
                // 2. Approved by division head but not yet approved by VP
                $query->where(function ($q) {
                    $q->where('divisionhead_approved', null)
                      ->orWhere(function ($q2) {
                          $q2->where('divisionhead_approved', true)
                             ->where('vp_approved', null);
                      });
                });
                break;
        }
        
        $travelOrders = $query->with('position')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('travel-orders.index', compact('travelOrders', 'tab'));
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
            'departure_time' => 'nullable|string|max:20',
            'purpose' => 'required|string|max:500',
            'position_id' => 'required|exists:emp_positions,id',
        ]);

        $user = Auth::user();
        $employee = $user->employee;
        
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
            $cleanTime = trim($request->departure_time);
            
            if (preg_match('/([01]?[0-9]|2[0-3]):([0-5][0-9])/', $cleanTime, $matches)) {
                $departureTime = $matches[1] . ':' . $matches[2];
            } elseif (preg_match('/([01]?[0-9]|2[0-3])[.:]?([0-5][0-9])/', $cleanTime, $matches)) {
                $departureTime = sprintf('%02d:%02d', $matches[1], $matches[2]);
            }
        }

        // Create the travel order
        $travelOrder = TravelOrder::create([
            'employee_id' => $employee->id,
            'emp_position_id' => $request->position_id,
            'destination' => $request->destination,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'departure_time' => $departureTime,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        return redirect()->route('unithead.travel-orders.index', ['tab' => 'pending'])
            ->with('success', 'Travel order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TravelOrder $travelOrder): View
    {
        // Ensure the unit head can only view their own travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        return view('travel-orders.show', compact('travelOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TravelOrder $travelOrder): View
    {
        // Ensure the unit head can only edit their own pending travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow editing if not yet approved by division head or VP
        if (!is_null($travelOrder->divisionhead_approved) || !is_null($travelOrder->vp_approved)) {
            abort(403);
        }
        
        return view('travel-orders.edit', compact('travelOrder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the unit head can only update their own pending travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow updating if not yet approved by division head or VP
        if (!is_null($travelOrder->divisionhead_approved) || !is_null($travelOrder->vp_approved)) {
            abort(403);
        }
        
        $request->validate([
            'destination' => 'required|string|max:255',
            'date_from' => 'required|date|before_or_equal:date_to',
            'date_to' => 'required|date|after_or_equal:date_from',
            'departure_time' => 'nullable|string|max:20',
            'purpose' => 'required|string|max:500',
        ]);

        // Process departure_time to ensure it's in the correct format or null
        $departureTime = null;
        if (!empty($request->departure_time)) {
            $cleanTime = trim($request->departure_time);
            
            if (preg_match('/([01]?[0-9]|2[0-3]):([0-5][0-9])/', $cleanTime, $matches)) {
                $departureTime = $matches[1] . ':' . $matches[2];
            } elseif (preg_match('/([01]?[0-9]|2[0-3])[.:]?([0-5][0-9])/', $cleanTime, $matches)) {
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

        return redirect()->route('unithead.travel-orders.index', ['tab' => 'pending'])
            ->with('success', 'Travel order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the unit head can only delete their own pending travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow deleting if not yet approved by division head or VP
        if (!is_null($travelOrder->divisionhead_approved) || !is_null($travelOrder->vp_approved)) {
            abort(403);
        }
        
        $travelOrder->delete();

        return redirect()->route('unithead.travel-orders.index', ['tab' => 'pending'])
            ->with('success', 'Travel order deleted successfully.');
    }

    /**
     * Generate PDF for unit head's own travel order (using regular employee template as basis)
     */
    public function generatePDF(TravelOrder $travelOrder): Response
    {
        // Ensure the unit head can only view their own travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
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
        
        // Get supervisor information
        $supervisorInfo = $this->getSupervisorInfo($employee);
        
        // Load Excel template (using the same template as regular employees)
        $templatePath = public_path('templates/travel_order_template.xlsx');
        if (!file_exists($templatePath)) {
            abort(500, 'Travel order template not found');
        }
        
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        // Fill the Excel cells with data (EXACT COPY FROM REGULAR EMPLOYEE TEMPLATE)
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
        $sheet->setCellValue('I41', $fullName);  
        $sheet->setCellValue('I42', $positionName);
        
        // Get employee role and approval workflow
        $employeeRole = $this->getEmployeeRole($employee);
        $workflow = $this->getApprovalWorkflow($employee);
        
        // Initialize officers
        $higherRankOfficer = ['name' => '', 'position' => ''];
        $lowerRankOfficer = ['name' => '', 'position' => ''];
        
        // Get the position associated with this travel order
        $travelOrderPosition = $travelOrder->position;
        
        // For unit heads: H19/H20 = VP/President, I45/I46 = Division Head
        // Always show both approvers regardless of approval status
        
        // Get VP or President (H19/H20) - check if President's Office first
        $vpOrPresident = $this->getApproverEmployee('VP_OR_PRESIDENT', $travelOrderPosition);
        if ($vpOrPresident) {
            $higherRankOfficer['name'] = $vpOrPresident->first_name . ' ' . $vpOrPresident->last_name;
            // Determine position based on office
            if ($travelOrderPosition && $travelOrderPosition->office) {
                // Only consider it President's Office if it's specifically the Office of the University President
                // Not just any office that contains "President" in the name
                $officeName = strtolower($travelOrderPosition->office->office_name);
                if (strpos($officeName, 'office of the university president') !== false || 
                    strpos($officeName, 'office of the president') !== false) {
                    $higherRankOfficer['position'] = $vpOrPresident->position ? $vpOrPresident->position->position_name : 'University President';
                } else {
                    $higherRankOfficer['position'] = $vpOrPresident->position ? $vpOrPresident->position->position_name : 'Vice President';
                }
            } else {
                $higherRankOfficer['position'] = $vpOrPresident->position ? $vpOrPresident->position->position_name : 'Vice President';
            }
        }
        
        // Get Division Head (I45/I46)
        $divisionHead = $this->getApproverEmployee('DIVISION_HEAD', $travelOrderPosition);
        if ($divisionHead) {
            $lowerRankOfficer['name'] = $divisionHead->first_name . ' ' . $divisionHead->last_name;
            $lowerRankOfficer['position'] = $divisionHead->position ? $divisionHead->position->position_name : 'Division Head';
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
        
        // Add individual approval status indicators
        // Show approval status for each approver
        
        // Unit Head approval status (if applicable to this role)
        if (!is_null($travelOrder->head_approved)) {
            $status = $travelOrder->head_approved ? 'APPROVED' : 'DECLINED/CANCELLED';
            $timestamp = $travelOrder->head_approved_at ? $travelOrder->head_approved_at->format('M j, Y g:i A') : '';
            // Add indicator next to Unit Head name (you can add this to a specific cell if needed)
        }
        
        // Division Head approval status
        if (!is_null($travelOrder->divisionhead_approved)) {
            $status = $travelOrder->divisionhead_approved ? 'APPROVED' : 'DECLINED/CANCELLED';
            $timestamp = $travelOrder->divisionhead_approved_at ? $travelOrder->divisionhead_approved_at->format('M j, Y g:i A') : '';
            // Add indicator next to Division Head name
        }
        
        // VP approval status
        if (!is_null($travelOrder->vp_approved)) {
            $status = $travelOrder->vp_approved ? 'APPROVED' : 'DECLINED/CANCELLED';
            $timestamp = $travelOrder->vp_approved_at ? $travelOrder->vp_approved_at->format('M j, Y g:i A') : '';
            // Add indicator next to VP name
        }
        
        // President approval status
        if (!is_null($travelOrder->president_approved)) {
            $status = $travelOrder->president_approved ? 'APPROVED' : 'DECLINED/CANCELLED';
            $timestamp = $travelOrder->president_approved_at ? $travelOrder->president_approved_at->format('M j, Y g:i A') : '';
            // Add indicator next to President name
        }
        
        // Add approval status in cells H17 (Division Head/Unit Head) and I43 (VP/President)
        // H17: Show Division Head approval status when available (since unit head is submitting this travel order)
        if ($travelOrder->divisionhead_approved_at) {
            $divisionHeadStatus = $travelOrder->divisionhead_approved ? 'APPROVED' : 'DECLINED';
            $divisionHeadTimestamp = $travelOrder->divisionhead_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('H17', $divisionHeadStatus . ' ' . $divisionHeadTimestamp);
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
        
        // Save Excel to temporary file
        $excelTemp = tempnam(sys_get_temp_dir(), 'travel_order_') . '.xlsx';
        IOFactory::createWriter($spreadsheet, 'Xlsx')->save($excelTemp);
        
        // Check if ConvertAPI is configured
        $convertApiSecret = config('services.convertapi.secret');
        
        if (!empty($convertApiSecret)) {
            try {
                // Use ConvertAPI to convert Excel to PDF
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
                
                return response()->json([
                    'error' => 'PDF conversion failed.',
                    'message' => $e->getMessage(),
                ], 500);
            }
        } else {
            // Fallback to MPDF if ConvertAPI is not configured
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
        }
        
        // Return PDF response
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="travel_order_' . $travelOrder->id . '.pdf"');
    }
        
    /**
     * Get the correct approving VP based on organizational hierarchy
     * 
     * @param mixed $position The employee position
     * @return mixed The approving VP employee or null
     */
    private function getApprovingVP($position)
    {
        if (!$position) {
            return null;
        }
            
        // Get the office of the position
        $office = $position->office;
        if (!$office) {
            return null;
        }
            
        // Check if this is the President's Office (no VP, goes directly to President)
        if (stripos($office->office_name, 'President') !== false) {
            // Return null - no VP approval needed, goes directly to President
            return null;
        }
            
        // For other offices, find the VP in charge of that office
        // This would typically be based on office_id matching VP's primary position
        $vp = \App\Models\Employee::whereHas('officer', function($query) {
            $query->where('vp', true);
        })->whereHas('positions', function($query) use ($office) {
            $query->where('office_id', $office->id_office);
        })->first();
            
        // Fallback: if no specific VP found, get any VP
        if (!$vp) {
            $vp = \App\Models\Employee::whereHas('officer', function($query) {
                $query->where('vp', true);
            })->first();
        }
            
        return $vp;
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
                    // Only go to President if it's specifically the Office of the University President
                    $officeName = strtolower($employeePosition->office->office_name);
                    if (strpos($officeName, 'office of the university president') !== false || 
                        strpos($officeName, 'office of the president') !== false) {
                        // President's Office - go directly to President
                        return \App\Models\Employee::whereHas('officer', function($query) {
                            $query->where('president', true);
                        })->first();
                    }
                }
                // Regular office (including VP offices) - find VP
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
                })->first();
                
                if ($divisionHead) {
                    $name = $divisionHead->first_name . ' ' . $divisionHead->last_name;
                    $position = $divisionHead->position ? $divisionHead->position->position_name : 'Division Head';
                }
            }
            return ['name' => $name, 'position' => $position];
        }
        
        // For regular employees - supervisor is Unit Head
        $head = \App\Models\Employee::whereHas('positions', function($query) {
            $query->where('is_unit_head', true);
        })
        ->whereHas('positions', function($query) use ($employee) {
            $query->where('unit_id', $employee->getUnitIdAttribute());
        })->first();
        
        if ($head) {
            $name = $head->first_name . ' ' . $head->last_name;
            $position = $head->position ? $head->position->position_name : 'Unit Head';
        } else {
            // Fallback to any unit head
            $head = \App\Models\Employee::whereHas('positions', function($query) {
                $query->where('is_unit_head', true);
            })->first();
            
            if ($head) {
                $name = $head->first_name . ' ' . $head->last_name;
                $position = $head->position ? $head->position->position_name : 'Unit Head';
            }
        }
        
        return ['name' => $name, 'position' => $position];
    }
}