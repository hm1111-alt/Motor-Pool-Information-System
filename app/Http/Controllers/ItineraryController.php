<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ConvertApi\ConvertApi;
use Carbon\Carbon;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pending');
        $search = $request->get('search');
        
        // Get all itineraries for all tabs (no filtering here)
        $query = Itinerary::with(['travelOrder', 'vehicle', 'driver'])
            ->orderBy('date_from', 'desc')
            ->orderBy('departure_time', 'asc');
        
        // Apply search filter if provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('destination', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhereHas('driver', function($driverQuery) use ($search) {
                      $driverQuery->where('first_name', 'like', "%{$search}%")
                                 ->orWhere('last_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('vehicle', function($vehicleQuery) use ($search) {
                      $vehicleQuery->where('make', 'like', "%{$search}%")
                                  ->orWhere('model', 'like', "%{$search}%");
                  });
            });
        }
        
        // Paginate the results
        $allItineraries = $query->paginate(10);
        
        // Filter the current tab's data for display
        $currentTabItineraries = $allItineraries->filter(function($itinerary) use ($tab) {
            switch ($tab) {
                case 'approved':
                    return $itinerary->status === 'Approved';
                case 'cancelled':
                    return $itinerary->status === 'Cancelled';
                case 'pending':
                default:
                    return $itinerary->status === 'Not yet Approved';
            }
        });
        
        // Create a new paginator for the filtered results
        $currentPage = $allItineraries->currentPage();
        $perPage = $allItineraries->perPage();
        $filteredItems = $currentTabItineraries->values(); // Reset keys
        $totalFiltered = $filteredItems->count();
        
        // Create a custom paginator for the filtered results
        $paginatedFiltered = new \Illuminate\Pagination\LengthAwarePaginator(
            $filteredItems,
            $totalFiltered,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
        
        // Add the search parameter to pagination links
        $paginatedFiltered->appends(['search' => $search, 'tab' => $tab]);
        
        // Get data for the modal
        $vehicles = \App\Models\Vehicle::whereIn('status', ['Available', 'Active'])->get();
        $drivers = \App\Models\Driver::where('availability_status', 'Active')->get();
        $travelOrders = TravelOrder::where('status', 'approved')->get();

        return view('itineraries.index', [
            'allItineraries' => $allItineraries,
            'currentTabItineraries' => $paginatedFiltered,
            'tab' => $tab,
            'search' => $search,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'travelOrders' => $travelOrders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = \App\Models\Vehicle::whereIn('status', ['Available', 'Active'])->get();
        $drivers = \App\Models\Driver::where('availability_status', 'Active')->get();
        $travelOrders = TravelOrder::where('status', 'approved')
            ->whereDoesntHave('itinerary')
            ->get();
        
        // Determine which layout to use based on user's role
        $user = auth()->user();
        $isMotorpoolAdmin = $user && $user->hasRole(\App\Models\User::ROLE_MOTORPOOL_ADMIN);
        $isAdmin = $user && $user->hasRole(\App\Models\User::ROLE_ADMIN);
        
        if ($isMotorpoolAdmin || $isAdmin) {
            $layout = 'itineraries.create-motorpool';
            $backUrl = route('itinerary.index'); // Back to itinerary index for motorpool admin
        } else {
            $layout = 'itineraries.create-employee';
            $backUrl = auth()->user() && auth()->user()->employee ? 
                      (auth()->user()->employee->is_vp ? route('vp.travel-orders.index') : 
                       (auth()->user()->employee->is_head ? route('unithead.travel-orders.index') : 
                        route('dashboard'))) : route('dashboard');
        }
        
        return view($layout, compact('vehicles', 'drivers', 'travelOrders', 'backUrl'));
    }
    
    /**
     * Show the itinerary creation modal
     */
    public function createModal()
    {
        $vehicles = \App\Models\Vehicle::whereIn('status', ['Available', 'Active'])->get();
        $drivers = \App\Models\Driver::where('availability_status', 'Active')->get();
        $travelOrders = TravelOrder::where('status', 'approved')
            ->whereDoesntHave('itinerary')
            ->get();
        
        // Get trip tickets to check availability
        $tripTickets = \App\Models\TripTicket::with(['itinerary.vehicle', 'itinerary.driver'])->get();
        
        return view('itineraries.modals.create-itinerary-modal', compact('vehicles', 'drivers', 'travelOrders', 'tripTickets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'destination' => 'required|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'departure_time' => 'required',
            'purpose' => 'required|string',
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $itinerary = Itinerary::create($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Itinerary created successfully!',
                'itinerary' => $itinerary
            ]);
        }

        return redirect()->route('itinerary.index')->with('success', 'Itinerary created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Itinerary $itinerary)
    {
        $itinerary->load(['vehicle', 'driver', 'travelOrder']);
        return view('itineraries.show', compact('itinerary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Itinerary $itinerary)
    {
        $vehicles = \App\Models\Vehicle::whereIn('status', ['Available', 'Active'])->get();
        $drivers = \App\Models\Driver::where('availability_status', 'Active')->get();
        $travelOrders = TravelOrder::where('status', 'approved')->get();
        
        // Determine which layout to use based on user's role
        $user = auth()->user();
        $isMotorpoolAdmin = $user && $user->hasRole(\App\Models\User::ROLE_MOTORPOOL_ADMIN);
        $isAdmin = $user && $user->hasRole(\App\Models\User::ROLE_ADMIN);
        
        if ($isMotorpoolAdmin || $isAdmin) {
            $layout = 'itineraries.edit-motorpool';
            $backUrl = route('itinerary.index'); // Back to itinerary index for motorpool admin
        } else {
            $layout = 'itineraries.edit-employee';
            $backUrl = auth()->user() && auth()->user()->employee ? 
                      (auth()->user()->employee->is_vp ? route('vp.travel-orders.index') : 
                       (auth()->user()->employee->is_head ? route('unithead.travel-orders.index') : 
                        route('dashboard'))) : route('dashboard');
        }
        
        return view($layout, compact('itinerary', 'vehicles', 'drivers', 'travelOrders', 'backUrl'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Itinerary $itinerary)
    {
        $request->validate([
            'destination' => 'required|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'departure_time' => 'required',
            'purpose' => 'required|string',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        $itinerary->update($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Itinerary updated successfully!',
                'itinerary' => $itinerary
            ]);
        }

        return redirect()->route('itinerary.index')->with('success', 'Itinerary updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Itinerary $itinerary)
    {
        $itinerary->delete();
        return redirect()->route('itinerary.index')->with('success', 'Itinerary archived successfully.');
    }
    
    /**
     * Generate PDF for itinerary
     */
    public function generatePDF(Itinerary $itinerary): Response
    {
        // Load the itinerary with all necessary relationships
        $itinerary->load(['vehicle', 'driver', 'travelOrder.employee']);
        
        // Get driver information
        $driver = $itinerary->driver;
        $full_name = $driver ? $driver->full_name : 'N/A';
        $position = $driver ? $driver->position : 'N/A';
        $official_station = $driver ? $driver->official_station : 'N/A';
        
        // Get itinerary details
        $purpose = $itinerary->purpose ?? 'N/A';
        $destination = $itinerary->destination ?? 'N/A';
        
        // Get vehicle details
        $vehicle_model = $itinerary->vehicle ? $itinerary->vehicle->model : 'N/A';
        $plate_number = $itinerary->vehicle ? $itinerary->vehicle->plate_number : 'N/A';
        
        // Get date details
        $date_from = $itinerary->date_from ? Carbon::parse($itinerary->date_from)->format('F d, Y') : 'N/A';
        $date_to = '';
        if ($itinerary->date_to && $itinerary->date_to != $itinerary->date_from) {
            $date_to = Carbon::parse($itinerary->date_to)->format('F d, Y');
        }
        
        // Get departure time
        $departure_time = '-';
        if ($itinerary->departure_time) {
            $departure_time = Carbon::parse($itinerary->departure_time)->format('h:i A');
        }
        
        // Load the Excel template
        $templatePath = public_path('templates/itinerary_template.xlsx');
        if (!file_exists($templatePath)) {
            return response()->json(['error' => 'Itinerary template not found.'], 500);
        }
        
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        // === DRIVER DETAILS ===
        $sheet->setCellValue('C9', $full_name);
        $sheet->setCellValue('E23', $full_name);
        $sheet->setCellValue('E24', $position);
        $sheet->setCellValue('E25', $official_station);
        
        // === ITINERARY DETAILS ===
        $sheet->setCellValue('C10', $purpose);
        $sheet->setCellValue('E26', $purpose);
        $sheet->setCellValue('G11', $destination);
        $sheet->setCellValue('D34', $destination);
        $sheet->setCellValue('G34', $departure_time);
        
        // === VEHICLE DETAILS ===
        $sheet->setCellValue('I34', $vehicle_model);
        $sheet->setCellValue('I35', $plate_number);
        
        // === DRIVER DETAILS (NEW) ===
        $sheet->setCellValue('I41', $full_name);  // Driver name
        $sheet->setCellValue('I42', $position);   // Driver position
        
        // === OFFICER DETAILS WITH APPROVAL HIERARCHY (NEW) ===
        // According to requirements:
        // H19: VP of Office of the Vice President for Administration (higher authority)
        // I45: Unit Head of Transportation Services (lower authority)
        $higherRankOfficer = [];
        $lowerRankOfficer = [];
        
        // Find the Vice President for Administration from the Office of the Vice President for Administration
        $vp = \App\Models\Employee::whereHas('positions', function($query) {
            $query->whereHas('office', function($officeQuery) {
                $officeQuery->where('office_name', 'Office of the Vice President for Administration');
            });
            $query->where('is_vp', true);
        })->first();
        
        // If not found in positions, check if the employee has VP status in the officers table
        if (!$vp) {
            $vp = \App\Models\Employee::whereHas('officer', function($query) {
                $query->where('vp', true);
            })->whereHas('positions', function($positionQuery) {
                $positionQuery->whereHas('office', function($officeQuery) {
                    $officeQuery->where('office_name', 'Office of the Vice President for Administration');
                });
            })->first();
        }
        
        // If not found, try to find VP by position name
        if (!$vp) {
            $vp = \App\Models\Employee::whereHas('positions', function($query) {
                $query->where('position_name', 'LIKE', '%Vice President for Administration%');
                $query->where('is_vp', true);
            })->first();
        }
        
        // If still not found in positions, check by position name in officers table
        if (!$vp) {
            $vp = \App\Models\Employee::whereHas('positions', function($positionQuery) {
                $positionQuery->where('position_name', 'LIKE', '%Vice President for Administration%');
            })->whereHas('officer', function($officerQuery) {
                $officerQuery->where('vp', true);
            })->first();
        }
        
        // If still not found, try a general VP search
        if (!$vp) {
            $vp = \App\Models\Employee::whereHas('positions', function($query) {
                $query->where('is_vp', true);
            })->orWhereHas('officer', function($query) {
                $query->where('vp', true);
            })->first();
        }
        
        if ($vp) {
            $higherRankOfficer = [
                'name' => $vp->first_name . ' ' . $vp->last_name,
                'position' => $vp->position ? $vp->position->position_name : 'Vice President for Administration'
            ];
        } else {
            // Fallback to default if no specific VP found
            $higherRankOfficer = [
                'name' => 'Office of the Vice President for Administration',
                'position' => 'Vice President for Administration'
            ];
        }

        // Find the Unit Head of Transportation Services
        $transportationHead = \App\Models\Employee::whereHas('positions', function($query) {
            $query->where('position_name', 'LIKE', '%Head of Transportation Services%');
            $query->where('unit_id', 26); // Transportation Services unit ID
        })->first();
        
        // If not found through positions, try the officers table
        if (!$transportationHead) {
            $transportationHead = \App\Models\Employee::whereHas('officer', function($query) {
                $query->where('unit_head', true);
            })->whereHas('positions', function($query) {
                $query->where('unit_id', 26); // Transportation Services unit ID
            })->first();
        }
        
        // If still not found, try to find unit head of transportation services unit
        if (!$transportationHead) {
            $transportationHead = \App\Models\Employee::whereHas('positions', function($query) {
                $query->where('is_unit_head', true);
                $query->where('unit_id', 26); // Transportation Services unit ID
            })->first();
        }
        
        if ($transportationHead) {
            $lowerRankOfficer = [
                'name' => $transportationHead->first_name . ' ' . $transportationHead->last_name,
                'position' => $transportationHead->position ? $transportationHead->position->position_name : 'Head of Transportation Services'
            ];
        } else {
            // Fallback to default if no specific transportation head found
            $lowerRankOfficer = [
                'name' => 'Head of Transportation Services',
                'position' => 'Head of Transportation Services'
            ];
        }
        
        // Set the officers in the correct cells
        // Higher rank officer in H19 (approving authority) and H20 (position)
        $sheet->setCellValue('H19', $higherRankOfficer['name']);  // Higher rank approving officer name
        $sheet->setCellValue('H20', $higherRankOfficer['position']);  // Higher rank approving officer position
        
        // Lower rank officer in I45 (approving authority) and I46 (position)
        $sheet->setCellValue('I45', $lowerRankOfficer['name']);  // Lower rank approving officer name
        $sheet->setCellValue('I46', $lowerRankOfficer['position']);  // Lower rank approving officer position
        
        // === STATUS STAMPS WITH TIMESTAMPS ===
        // H17: VP Approval Status with timestamp
        // Only show status if VP has taken action (approved or declined)
        if ($itinerary->vp_approved_at) {
            $vpTimestamp = $itinerary->vp_approved_at->format('M j, Y g:i A');
            if ($itinerary->vp_approved) {
                $sheet->setCellValue('H17', 'APPROVED ' . $vpTimestamp);
            } else {
                $sheet->setCellValue('H17', 'DECLINED ' . $vpTimestamp);
            }
        }
        // Leave H17 empty if no action taken yet
        
        // I43: Unit Head Approval Status with timestamp
        // Only show status if Unit Head has taken action (approved or declined)
        if ($itinerary->unit_head_approved_at) {
            $unitHeadTimestamp = $itinerary->unit_head_approved_at->format('M j, Y g:i A');
            if ($itinerary->unit_head_approved) {
                $sheet->setCellValue('I43', 'APPROVED ' . $unitHeadTimestamp);
            } else {
                $sheet->setCellValue('I43', 'DECLINED ' . $unitHeadTimestamp);
            }
        }
        // Leave I43 empty if no action taken yet
        
        // === DATE DETAILS ===
        $sheet->setCellValue('B34', $date_from);
        $sheet->setCellValue('B35', $date_to);
        
        // Save Excel temporarily for conversion
        $excelTemp = tempnam(sys_get_temp_dir(), 'itinerary_') . '.xlsx';
        IOFactory::createWriter($spreadsheet, 'Xlsx')->save($excelTemp);
        
        // Check if ConvertAPI is configured
        $convertApiSecret = config('services.convertapi.secret');
        
        if (!empty($convertApiSecret)) {
            try {
                // Use ConvertAPI to convert Excel to PDF
                ConvertApi::setApiCredentials($convertApiSecret);
                $result = ConvertApi::convert('pdf', ['File' => $excelTemp], 'xlsx');
                $pdfPath = tempnam(sys_get_temp_dir(), 'itinerary_pdf_') . '.pdf';
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
            $tempPdfPath = storage_path('app/temp_itinerary_' . $itinerary->id . '.pdf');
            
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
            ->header('Content-Disposition', 'inline; filename="itinerary_' . $itinerary->id . '.pdf"');
    }
}