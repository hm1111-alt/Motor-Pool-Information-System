<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelOrder;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use ConvertApi\ConvertApi;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class MotorpoolAdminTravelOrderController extends Controller
{
    /**
     * Display a listing of all approved travel orders.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        
        $query = TravelOrder::with(['employee', 'employee.user'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc');
            
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('employee', function($employeeQuery) use ($search) {
                    $employeeQuery->where('first_name', 'LIKE', "%{$search}%")
                                 ->orWhere('last_name', 'LIKE', "%{$search}%")
                                 ->orWhere('full_name', 'LIKE', "%{$search}%");
                })->orWhere('destination', 'LIKE', "%{$search}%")
                  ->orWhere('purpose', 'LIKE', "%{$search}%");
            });
        }
        
        $travelOrders = $query->paginate(10)->appends(['search' => $search]);

        return view('motorpool-admin.travel-orders.index', compact('travelOrders', 'search'));
    }

    /**
     * Display the specified travel order.
     */
    public function show(TravelOrder $travelOrder): View
    {
        $travelOrder->load(['employee', 'employee.user']);
        
        return view('motorpool-admin.travel-orders.show', compact('travelOrder'));
    }
    
    /**
     * Generate PDF for travel order
     */
    public function generatePDF(TravelOrder $travelOrder): Response
    {
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
        
        // Load the Excel template
        $templatePath = public_path('templates/travel_order_template.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        // Fill the Excel cells with data
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
        $travelOrderPosition = $employee->positions()->where('is_primary', true)->first();
        
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
    }