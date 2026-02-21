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

class PresidentOwnTravelOrderController extends Controller
{
    /**
     * Display a listing of the president's own travel orders.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get all travel orders for this president
        // President travel orders are automatically approved and sent to motorpool
        $travelOrders = TravelOrder::with('employee', 'position') // Eager load employee and position relationships
            ->where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('travel-orders.president-index', compact('travelOrders'));
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
        
        return view('travel-orders.president-create', compact('positions'));
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
            'status' => 'approved', // President travel orders are automatically approved
            'president_approved' => true, // Mark as approved by president
            'president_approved_at' => now(), // Set approval timestamp
        ]);

        return redirect()->route('president.travel-orders.index')
            ->with('success', 'Travel order created successfully and sent to motorpool.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TravelOrder $travelOrder): View
    {
        // Ensure the president can only view their own travel orders
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
        // Ensure the president can only edit their own travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow editing if status is still pending
        if ($travelOrder->status !== 'pending') {
            abort(403);
        }
        
        return view('travel-orders.edit', compact('travelOrder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the president can only update their own travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow updating if status is still pending
        if ($travelOrder->status !== 'pending') {
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

        return redirect()->route('president.travel-orders.index')
            ->with('success', 'Travel order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the president can only delete their own travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow deleting if status is still pending
        if ($travelOrder->status !== 'pending') {
            abort(403);
        }
        
        $travelOrder->delete();

        return redirect()->route('president.travel-orders.index')
            ->with('success', 'Travel order deleted successfully.');
    }
    
    /**
     * Generate PDF for president's own travel order
     */
    public function generatePDF(TravelOrder $travelOrder): Response
    {
        // Ensure the president can only view their own travel orders
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
        
        // Get President information (for self-reference)
        $president = \App\Models\Employee::whereHas('officer', function($query) {
            $query->where('president', true);
        })->first();
        
        $presidentName = $president ? $president->first_name . ' ' . $president->last_name : 'N/A';
        $presidentPosition = $president && $president->position ? $president->position->position_name : 'University President';
        
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
        
        // Fill in approver information (President as self-approver)
        $sheet->setCellValue('H19', $presidentName);
        $sheet->setCellValue('H20', $presidentPosition);
        $sheet->setCellValue('I41', $fullName);
        $sheet->setCellValue('I42', $positionName);
        $sheet->setCellValue('I45', $presidentName);
        $sheet->setCellValue('I46', $presidentPosition);
        
        // Add approval status in cells I17 and K43
        if ($travelOrder->president_approved_at) {
            $presidentStatus = $travelOrder->president_approved ? 'APPROVED' : 'DECLINED';
            $presidentTimestamp = $travelOrder->president_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('I17', $presidentStatus . ' ' . $presidentTimestamp);
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
                    $tempPdfPath = storage_path('app/temp_president_travel_order_' . $travelOrder->id . '.pdf');
                    
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
                $tempPdfPath = storage_path('app/temp_president_travel_order_' . $travelOrder->id . '.pdf');
                
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
}