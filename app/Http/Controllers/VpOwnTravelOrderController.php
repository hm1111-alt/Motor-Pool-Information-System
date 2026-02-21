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

class VpOwnTravelOrderController extends Controller
{
    /**
     * Display a listing of the VP's own travel orders.
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
                // For VPs, approved means president has approved
                $query->where('president_approved', true);
                break;
            case 'cancelled':
                // For VPs, cancelled means president rejected
                $query->where('president_approved', false);
                break;
            case 'pending':
            default:
                // For VPs, pending means either not yet approved by president
                $query->where('president_approved', null);
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
            'date_to' => 'required|date|after_or_equal=date_from',
            'departure_time' => 'nullable|date_format:H:i',
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
        
        // Create the travel order
        $travelOrder = TravelOrder::create([
            'employee_id' => $employee->id,
            'emp_position_id' => $request->position_id, // Associate with the selected position
            'destination' => $request->destination,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'departure_time' => $request->departure_time,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);
        
        return redirect()->route('vp.travel-orders.index', ['tab' => 'pending'])
            ->with('success', 'Travel order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TravelOrder $travelOrder): View
    {
        // Ensure the VP can only view their own travel orders
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
        // Ensure the VP can only edit their own pending travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow editing if not yet approved by president
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        return view('travel-orders.edit', compact('travelOrder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the VP can only update their own pending travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow updating if not yet approved by president
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        $request->validate([
            'destination' => 'required|string|max:255',
            'date_from' => 'required|date|before_or_equal:date_to',
            'date_to' => 'required|date|after_or_equal:date_from',
            'departure_time' => 'nullable|date_format:H:i',
            'purpose' => 'required|string|max:500',
        ]);

        $travelOrder->update([
            'destination' => $request->destination,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'departure_time' => $request->departure_time,
            'purpose' => $request->purpose,
        ]);

        return redirect()->route('vp.travel-orders.index', ['tab' => 'pending'])
            ->with('success', 'Travel order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the VP can only delete their own pending travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow deleting if not yet approved by president
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        $travelOrder->delete();

        return redirect()->route('vp.travel-orders.index', ['tab' => 'pending'])
            ->with('success', 'Travel order deleted successfully.');
    }
    
    /**
     * Generate PDF for VP's own travel order
     */
    public function generatePDF(TravelOrder $travelOrder): Response
    {
        // Ensure the VP can only view their own travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Load the travel order with all necessary relationships
        $travelOrder->load([
            'employee', 
            'employee.user',
            'position',
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
        
        // For VPs: Show President in both H19 and I45
        // Get President information
        $president = \App\Models\Employee::whereHas('officer', function($query) {
            $query->where('president', true);
        })->first();
        
        $presidentName = $president ? $president->first_name . ' ' . $president->last_name : 'N/A';
        $presidentPosition = $president && $president->position ? $president->position->position_name : 'University President';
        
        // Set President in both H19/H20 and I45/I46
        $sheet->setCellValue('H19', $presidentName);  // President name in H19
        $sheet->setCellValue('H20', $presidentPosition);  // President position in H20
        $sheet->setCellValue('I45', $presidentName);  // President name in I45
        $sheet->setCellValue('I46', $presidentPosition);  // President position in I46
        
        $sheet->setCellValue('I41', $fullName);  // Employee name (signatory)
        $sheet->setCellValue('I42', $positionName);  // Employee position (signatory)
        
        // President approval status - shown when President has approved VP's travel order
        // For VP's own travel orders, President is the approving authority
        // When President approves, both H17 and I43 should show the approval status
        
        // President approval status (H17) - shown when President has approved
        if ($travelOrder->president_approved_at) {
            $presidentStatus = $travelOrder->president_approved ? 'APPROVED' : 'DECLINED';
            $presidentTimestamp = $travelOrder->president_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('H17', $presidentStatus . ' ' . $presidentTimestamp);
        }
        
        // President approval status (I43) - also shown when President has approved (for VP format)
        if ($travelOrder->president_approved_at) {
            $presidentStatus = $travelOrder->president_approved ? 'APPROVED' : 'DECLINED';
            $presidentTimestamp = $travelOrder->president_approved_at->format('M j, Y g:i A');
            $sheet->setCellValue('I43', $presidentStatus . ' ' . $presidentTimestamp);
        }
        
        // Save Excel temporarily for conversion
        $excelTemp = tempnam(sys_get_temp_dir(), 'vp_travel_order_') . '.xlsx';
        IOFactory::createWriter($spreadsheet, 'Xlsx')->save($excelTemp);
        
        // Check if ConvertAPI is configured
        $convertApiSecret = config('services.convertapi.secret');
        
        if (!empty($convertApiSecret)) {
            try {
                // Use ConvertAPI with enhanced parameters for better text rendering
                ConvertApi::setApiCredentials($convertApiSecret);
                
                $convertParams = [
                    'File' => $excelTemp,
                    'StoreFile' => true,
                    'PdfQuality' => '100',
                    'ImageDPI' => '300',
                    'TextRendering' => 'true',
                    'PreserveText' => 'true',
                    'EmbedFonts' => 'true',
                    'PdfA' => 'false',
                    'ExcelSheet' => $sheet->getTitle()
                ];
                
                $result = ConvertApi::convert('pdf', $convertParams, 'xlsx');
                $pdfPath = tempnam(sys_get_temp_dir(), 'vp_travel_order_pdf_') . '.pdf';
                $result->getFile()->save($pdfPath);
                
                // Read the generated PDF content
                $pdfContent = file_get_contents($pdfPath);
                
                // Clean up temporary files
                unlink($excelTemp);
                unlink($pdfPath);
                
                // Verify that the approval text is in the PDF
                $approvalText = 'APPROVED ' . $travelOrder->president_approved_at->format('M j, Y g:i A');
                if (strpos($pdfContent, $approvalText) === false) {
                    // If text is missing, try MPDF fallback
                    throw new \Exception('ConvertAPI failed to render approval text properly');
                }
            } catch (\Exception $e) {
                // Clean up the Excel temp file in case of error
                if (file_exists($excelTemp)) {
                    unlink($excelTemp);
                }
                
                // Fallback to MPDF
                try {
                    $tempPdfPath = storage_path('app/temp_vp_travel_order_' . $travelOrder->id . '.pdf');
                    
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
                $tempPdfPath = storage_path('app/temp_vp_travel_order_' . $travelOrder->id . '.pdf');
                
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
                
                return response()->json([
                    'error' => 'PDF generation failed.',
                    'message' => 'MPDF fallback failed: ' . $e->getMessage(),
                ], 500);
            }
        }
        
        // Return PDF response
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="vp_travel_order_' . $travelOrder->id . '.pdf"');
    }
}