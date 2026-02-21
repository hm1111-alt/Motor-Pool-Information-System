<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use App\Models\EmpPosition;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ConvertApi\ConvertApi;

class DivisionHeadOwnTravelOrderController extends Controller
{
    /**
     * Display a listing of the travel orders.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');

        // Get search term if provided
        $search = $request->get('search', '');

        // Get all travel orders for the current employee
        $query = TravelOrder::where('employee_id', $employee->id);

        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('destination', 'LIKE', "%{$search}%")
                  ->orWhere('purpose', 'LIKE', "%{$search}%")
                  ->orWhereHas('employee', function($q) use ($search) {
                      $q->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Apply tab-specific filtering
        switch ($tab) {
            case 'approved':
                // For division heads, approved means both VP and President have approved
                $query->where('vp_approved', true)
                      ->where('president_approved', true);
                break;
            case 'cancelled':
                // Cancelled if either VP or President rejected
                $query->where(function($q) {
                    $q->where('vp_approved', false)
                      ->orWhere('president_approved', false);
                });
                break;
            case 'pending':
            default:
                // For division heads, pending means not yet fully approved (either waiting for VP or President approval)
                $query->where(function($q) {
                    $q->whereNull('vp_approved')
                      ->orWhere(function($subQ) {
                          $subQ->where('vp_approved', true)
                               ->whereNull('president_approved');
                      });
                });
                break;
        }

        // Get paginated results with position information
        $travelOrders = $query->with('position', 'employee')->orderBy('created_at', 'desc')->paginate(10)->appends($request->except('page'));

        return view('travel-orders.index', compact('travelOrders', 'tab', 'search'));
    }

    /**
     * Show the form for creating a new travel order.
     */
    public function create(): View
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Get all positions for this employee
        $positions = $employee->positions;

        return view('travel-orders.create', compact('positions'));
    }

    /**
     * Store a newly created travel order in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Validate that the user is a division head
        if (!$employee->is_divisionhead) {
            return Redirect::route('dashboard')
                ->with('error', 'Only division heads can create their own travel orders.');
        }

        $request->validate([
            'emp_position_id' => 'required|exists:emp_positions,id',
            'destination' => 'required|string|max:255',
            'date_from' => 'required|date|before_or_equal:date_to',
            'date_to' => 'required|date|after_or_equal:date_from',
            'departure_time' => 'nullable|date_format:H:i',
            'purpose' => 'required|string|max:500',
        ]);

        // Create the travel order with initial status as pending
        $travelOrder = TravelOrder::create([
            'employee_id' => $employee->id,
            'emp_position_id' => $request->emp_position_id,
            'destination' => $request->destination,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'departure_time' => $request->departure_time,
            'purpose' => $request->purpose,
            'status' => 'pending', // Initially pending
        ]);

        return Redirect::route('division-head-own-travel-orders.index')
            ->with('success', 'Travel order created successfully. Awaiting VP approval.');
    }

    /**
     * Display the specified travel order.
     */
    public function show(TravelOrder $travelOrder): View
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Ensure the logged-in user is the owner of the travel order
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }

        // Ensure the user is a division head
        if (!$employee->is_divisionhead) {
            return Redirect::route('dashboard')
                ->with('error', 'Access denied. Only division heads can view their own travel orders.');
        }

        return view('travel-orders.show', compact('travelOrder'));
    }

    /**
     * Show the form for editing the specified travel order.
     */
    public function edit(TravelOrder $travelOrder): View
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Ensure the logged-in user is the owner of the travel order
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }

        // Ensure the user is a division head
        if (!$employee->is_divisionhead) {
            return Redirect::route('dashboard')
                ->with('error', 'Access denied. Only division heads can edit their own travel orders.');
        }

        // Check if the travel order can be edited (not yet approved by anyone)
        if (!is_null($travelOrder->vp_approved)) {
            return Redirect::back()
                ->with('error', 'Cannot edit travel order after VP approval has started.');
        }

        $positions = $employee->positions;

        return view('travel-orders.edit', compact('travelOrder', 'positions'));
    }

    /**
     * Update the specified travel order in storage.
     */
    public function update(Request $request, TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Ensure the logged-in user is the owner of the travel order
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }

        // Ensure the user is a division head
        if (!$employee->is_divisionhead) {
            return Redirect::route('dashboard')
                ->with('error', 'Access denied. Only division heads can update their own travel orders.');
        }

        // Check if the travel order can be updated (not yet approved by anyone)
        if (!is_null($travelOrder->vp_approved)) {
            return Redirect::back()
                ->with('error', 'Cannot update travel order after VP approval has started.');
        }

        $request->validate([
            'emp_position_id' => 'required|exists:emp_positions,id',
            'destination' => 'required|string|max:255',
            'date_from' => 'required|date|before_or_equal:date_to',
            'date_to' => 'required|date|after_or_equal:date_from',
            'departure_time' => 'nullable|date_format:H:i',
            'purpose' => 'required|string|max:500',
        ]);

        $travelOrder->update([
            'emp_position_id' => $request->emp_position_id,
            'destination' => $request->destination,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'departure_time' => $request->departure_time,
            'purpose' => $request->purpose,
        ]);

        return Redirect::route('division-head-own-travel-orders.index')
            ->with('success', 'Travel order updated successfully.');
    }

    /**
     * Remove the specified travel order from storage.
     */
    public function destroy(TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Ensure the logged-in user is the owner of the travel order
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }

        // Ensure the user is a division head
        if (!$employee->is_divisionhead) {
            return Redirect::route('dashboard')
                ->with('error', 'Access denied. Only division heads can delete their own travel orders.');
        }

        // Check if the travel order can be deleted (not yet approved by anyone)
        if (!is_null($travelOrder->vp_approved)) {
            return Redirect::back()
                ->with('error', 'Cannot delete travel order after VP approval has started.');
        }

        $travelOrder->delete();

        return Redirect::route('division-head-own-travel-orders.index')
            ->with('success', 'Travel order deleted successfully.');
    }
    
    /**
     * Generate PDF for division head's own travel order
     */
    public function generatePDF(TravelOrder $travelOrder): Response
    {
        // Ensure the division head can only view their own travel orders
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
        
        // Load Excel template
        $templatePath = public_path('templates/travel_order_template.xlsx');
        if (!file_exists($templatePath)) {
            abort(500, 'Travel order template not found');
        }
        
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
        $sheet->setCellValue('I41', $fullName);  
        $sheet->setCellValue('I42', $positionName);
        
        // Get the position associated with this travel order
        $travelOrderPosition = $travelOrder->position;
        
        // For division heads: H19 = President, I45 = VP (or President if no VP and under President's Office)
        
        // Get President (H19) - always the President for division heads
        $president = $this->getApproverEmployee('PRESIDENT', $travelOrderPosition);
        $presidentName = '';
        $presidentPosition = '';
        
        if ($president) {
            $presidentName = $president->first_name . ' ' . $president->last_name;
            $presidentPosition = $president->position ? $president->position->position_name : 'University President';
        }
        
        // Get VP or President for I45
        $vpOrPresident = null;
        $vpOrPresidentName = '';
        $vpOrPresidentPosition = '';
        
        // Check if this is President's Office
        $isPresidentsOffice = false;
        if ($travelOrderPosition && $travelOrderPosition->office) {
            $officeName = strtolower($travelOrderPosition->office->office_name);
            if (strpos($officeName, 'office of the university president') !== false || 
                strpos($officeName, 'office of the president') !== false) {
                $isPresidentsOffice = true;
            }
        }
        
        if ($isPresidentsOffice) {
            // If it's President's Office, use President for I45 as well
            $vpOrPresident = $president;
            $vpOrPresidentName = $presidentName;
            $vpOrPresidentPosition = $presidentPosition;
        } else {
            // Otherwise, try to find VP
            $vp = $this->getApproverEmployee('VP', $travelOrderPosition);
            if ($vp) {
                $vpOrPresident = $vp;
                $vpOrPresidentName = $vp->first_name . ' ' . $vp->last_name;
                $vpOrPresidentPosition = $vp->position ? $vp->position->position_name : 'Vice President';
            } else {
                // If no VP found, fallback to President
                $vpOrPresident = $president;
                $vpOrPresidentName = $presidentName;
                $vpOrPresidentPosition = $presidentPosition;
            }
        }
        
        // Set the approvers in the correct cells
        // President in H19 (approving office) and H20 (position)
        $sheet->setCellValue('H19', $presidentName ?: 'N/A');
        $sheet->setCellValue('H20', $presidentPosition ?: 'N/A');
        
        // VP or President in I45 (approving office) and I46 (position)
        $sheet->setCellValue('I45', $vpOrPresidentName ?: 'N/A');
        $sheet->setCellValue('I46', $vpOrPresidentPosition ?: 'N/A');
        
        // Add approval status indicators
        // For Division Head travel orders:
        // I17: Show President approval status (highest level approver)
        // K43: Show VP approval status (first approver)
        // When President has approved, BOTH I17 and K43 show status (as requested)
        
        // President approval status (I17) - always shown when President has approved
        if ($travelOrder->president_approved_at) {
            $presidentStatus = $travelOrder->president_approved ? 'APPROVED' : 'DECLINED';
            $presidentTimestamp = $travelOrder->president_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('I17', $presidentStatus . ' ' . $presidentTimestamp);
        }
        
        // VP approval status (K43) - always shown when VP has approved
        if ($travelOrder->vp_approved_at) {
            $vpStatus = $travelOrder->vp_approved ? 'APPROVED' : 'DECLINED';
            $vpTimestamp = $travelOrder->vp_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('K43', $vpStatus . ' ' . $vpTimestamp);
        }
        
        // Special case: When President has approved, also show President status in K43
        // This ensures both I17 and K43 have status stamps when President approves
        if ($travelOrder->president_approved_at && !$travelOrder->vp_approved_at) {
            $presidentStatus = $travelOrder->president_approved ? 'APPROVED' : 'DECLINED';
            $presidentTimestamp = $travelOrder->president_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('K43', $presidentStatus . ' ' . $presidentTimestamp);
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
     * Get the actual approver employee based on role and position
     */
    private function getApproverEmployee($approverRole, $employeePosition)
    {
        switch ($approverRole) {
            case 'PRESIDENT':
                // Find President
                return \App\Models\Employee::whereHas('officer', function($query) {
                    $query->where('president', true);
                })->first();
                
            case 'VP':
                // Find VP
                return \App\Models\Employee::whereHas('officer', function($query) {
                    $query->where('vp', true);
                })->first();
                
            default:
                return null;
        }
    }
}