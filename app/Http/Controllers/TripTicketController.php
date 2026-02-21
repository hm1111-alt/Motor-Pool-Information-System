<?php

namespace App\Http\Controllers;

use App\Models\TripTicket;
use App\Models\Itinerary;
use App\Models\TravelOrder;
use App\Services\DistanceEstimationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ConvertApi\ConvertApi;
use Carbon\Carbon;

class TripTicketController extends Controller
{
    /**
     * Display a listing of the trip tickets.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $tab = $request->get('tab', 'pending');
        
        // Get pending trip tickets (status: Pending)
        $pendingQuery = TripTicket::with(['itinerary.driver', 'itinerary.vehicle', 'itinerary.travelOrder'])
            ->where('status', 'Pending');
        
        if ($search) {
            $pendingQuery->where(function($q) use ($search) {
                $q->whereHas('itinerary.driver', function($driverQuery) use ($search) {
                    $driverQuery->where('first_name', 'LIKE', '%' . $search . '%')
                              ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                              ->orWhere('full_name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('itinerary.vehicle', function($vehicleQuery) use ($search) {
                    $vehicleQuery->where('plate_number', 'LIKE', '%' . $search . '%')
                               ->orWhere('make', 'LIKE', '%' . $search . '%')
                               ->orWhere('model', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('head_of_party', 'LIKE', '%' . $search . '%');
            });
        }
        $pendingTripTickets = $pendingQuery->paginate(10, ['*'], 'pending_page');
        
        // Get ongoing trip tickets (status: Approved)
        $ongoingQuery = TripTicket::with(['itinerary.driver', 'itinerary.vehicle', 'itinerary.travelOrder'])
            ->where('status', 'Approved');
        
        if ($search) {
            $ongoingQuery->where(function($q) use ($search) {
                $q->whereHas('itinerary.driver', function($driverQuery) use ($search) {
                    $driverQuery->where('first_name', 'LIKE', '%' . $search . '%')
                              ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                              ->orWhere('full_name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('itinerary.vehicle', function($vehicleQuery) use ($search) {
                    $vehicleQuery->where('plate_number', 'LIKE', '%' . $search . '%')
                               ->orWhere('make', 'LIKE', '%' . $search . '%')
                               ->orWhere('model', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('head_of_party', 'LIKE', '%' . $search . '%');
            });
        }
        $ongoingTripTickets = $ongoingQuery->paginate(10, ['*'], 'ongoing_page');
        
        // Get completed trip tickets (status: Completed or Cancelled)
        $completedQuery = TripTicket::with(['itinerary.driver', 'itinerary.vehicle', 'itinerary.travelOrder'])
            ->whereIn('status', ['Completed', 'Cancelled']);
        
        if ($search) {
            $completedQuery->where(function($q) use ($search) {
                $q->whereHas('itinerary.driver', function($driverQuery) use ($search) {
                    $driverQuery->where('first_name', 'LIKE', '%' . $search . '%')
                              ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                              ->orWhere('full_name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('itinerary.vehicle', function($vehicleQuery) use ($search) {
                    $vehicleQuery->where('plate_number', 'LIKE', '%' . $search . '%')
                               ->orWhere('make', 'LIKE', '%' . $search . '%')
                               ->orWhere('model', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('head_of_party', 'LIKE', '%' . $search . '%');
            });
        }
        $completedTripTickets = $completedQuery->paginate(10, ['*'], 'completed_page');
        
        return view('trip-tickets.index', compact('pendingTripTickets', 'ongoingTripTickets', 'completedTripTickets', 'search', 'tab'));
    }

    /**
     * Show the form for creating a new trip ticket.
     */
    public function create(): View
    {
        // Get all approved itineraries that don't have trip tickets yet
        $itineraries = \App\Models\Itinerary::with(['driver', 'vehicle', 'travelOrder.employee'])
            ->where('status', 'Approved')
            ->whereDoesntHave('tripTickets')
            ->get();
        
        // Get travel orders that don't have itineraries yet
        $travelOrdersWithoutItinerary = \App\Models\TravelOrder::with(['employee'])
            ->whereDoesntHave('itinerary')
            ->select('travel_orders.*')
            ->get();
        
        return view('trip-tickets.create', compact('itineraries', 'travelOrdersWithoutItinerary'));
    }

    /**
     * Store a newly created trip ticket in storage.
     */
    public function store(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $request->validate([
            'itinerary_id' => 'required|exists:itineraries,id',
            'ticket_number' => 'required|string|unique:trip_tickets,ticket_number',
        ]);
        
        // Process passengers if provided
        $passengers = [];
        if ($request->has('passenger_names') && is_array($request->passenger_names)) {
            $passengers = array_filter($request->passenger_names);
        }
        
        // Get the head of party
        $headOfParty = $request->input('head_of_party', null);
        $ticketNumber = $request->input('ticket_number');
        
        // Create the trip ticket
        $tripTicket = TripTicket::create([
            'itinerary_id' => $request->itinerary_id,
            'status' => 'Pending',
            'passengers' => $passengers,
            'head_of_party' => $headOfParty,
            'ticket_number' => $ticketNumber,
        ]);
        
        // Handle AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Trip ticket created successfully!',
                'trip_ticket' => $tripTicket->load(['itinerary.driver', 'itinerary.vehicle'])
            ]);
        }
        
        return redirect()->route('trip-tickets.index')
            ->with('success', 'Trip ticket created successfully.');
    }

    /**
     * Display the specified trip ticket.
     */
    public function show($id): View
    {
        $tripTicket = TripTicket::with(['itinerary.driver', 'itinerary.vehicle'])->findOrFail($id);
        
        return view('trip-tickets.show', compact('tripTicket'));
    }
    
    /**
     * Get trip ticket details for the modal
     */
    public function getDetails($id): \Illuminate\Http\JsonResponse
    {
        $tripTicket = TripTicket::with([
            'itinerary.driver',
            'itinerary.vehicle',
            'itinerary.travelOrder.employee'
        ])->findOrFail($id);
        
        // Format passengers as a simple array for the frontend
        $responseData = $tripTicket->toArray();
        
        // Ensure passengers is always an array, even if null
        if (!isset($responseData['passengers']) || $responseData['passengers'] === null) {
            $responseData['passengers'] = [];
        } elseif (is_string($responseData['passengers'])) {
            // If passengers is stored as a JSON string, decode it
            $decoded = json_decode($responseData['passengers'], true);
            $responseData['passengers'] = is_array($decoded) ? $decoded : [];
        } elseif (!is_array($responseData['passengers'])) {
            $responseData['passengers'] = [];
        }
        
        return response()->json($responseData);
    }

    /**
     * Show the form for editing the specified trip ticket.
     */
    public function edit($id): View
    {
        $tripTicket = TripTicket::with('itinerary')->findOrFail($id);
        
        // Get all approved itineraries (including the current one) for selection
        $itineraries = Itinerary::with(['driver', 'vehicle', 'travelOrder.employee'])
            ->where('unit_head_approved', true)
            ->where('vp_approved', true)
            ->whereNotNull('unit_head_approved_at')
            ->whereNotNull('vp_approved_at')
            ->where(function($query) use ($tripTicket) {
                // Either it doesn't have a trip ticket, or it's the current trip ticket's itinerary
                $query->whereDoesntHave('tripTickets')
                      ->orWhere('itineraries.id', $tripTicket->itinerary_id);
            })
            ->select('itineraries.*')
            ->get();
        
        // Get travel orders that don't have itineraries
        $travelOrdersWithoutItinerary = TravelOrder::with(['employee'])
            ->whereDoesntHave('itinerary')
            ->select('travel_orders.*')
            ->get();
        
        return view('trip-tickets.edit', compact('tripTicket', 'itineraries', 'travelOrdersWithoutItinerary'));
    }

    /**
     * Update the specified trip ticket in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'itinerary_id' => 'required|exists:itineraries,id',
        ]);
        
        $tripTicket = TripTicket::findOrFail($id);
        
        // Process passengers if provided
        $passengers = [];
        if ($request->has('passenger_names') && is_array($request->passenger_names)) {
            $passengers = array_filter($request->passenger_names);
        }
        
        // Get the head of party
        $headOfParty = $request->input('head_of_party', null);
        
        $tripTicket->update([
            'itinerary_id' => $request->itinerary_id,
            'passengers' => $passengers,
            'head_of_party' => $headOfParty,
        ]);
        
        return redirect()->route('trip-tickets.index')
            ->with('success', 'Trip ticket updated successfully.');
    }

    /**
     * Get passengers for a specific travel order
     */
    public function getPassengersForTravelOrder($id): \Illuminate\Http\JsonResponse
    {
        $travelOrder = TravelOrder::with(['employee'])->find($id);
        
        if (!$travelOrder) {
            return response()->json(['error' => 'Travel order not found'], 404);
        }
        
        $passengers = [];
        
        // Add the main employee who created the travel order
        if ($travelOrder->employee) {
            $passengers[] = [
                'name' => $travelOrder->employee->first_name . ' ' . $travelOrder->employee->last_name,
                'role' => 'Employee',
                'type' => 'employee'
            ];
        }
        
        return response()->json([
            'employee' => $travelOrder->employee ? [
                'first_name' => $travelOrder->employee->first_name,
                'last_name' => $travelOrder->employee->last_name
            ] : null,
            'passengers' => $passengers
        ]);
    }
    
    /**
     * Update the status of the specified trip ticket.
     */
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Completed,Cancelled,Archived',
        ]);

        $tripTicket = TripTicket::findOrFail($id);
        
        // Store the old status to check if it changed to 'Completed'
        $oldStatus = $tripTicket->status;
        
        $tripTicket->update([
            'status' => $request->status,
        ]);
        
        // If status changed to 'Completed', create vehicle travel history and update vehicle mileage
        if ($oldStatus !== 'Completed' && $request->status === 'Completed') {
            $this->createVehicleTravelHistory($tripTicket);
            $this->updateVehicleMileage($tripTicket);
        }
        
        $statusMessages = [
            'Approved' => 'Trip started successfully.',
            'Completed' => 'Trip completed successfully.',
            'Cancelled' => 'Trip cancelled successfully.',
            'Archived' => 'Trip archived successfully.',
            'Pending' => 'Trip status updated successfully.'
        ];
        
        $message = $statusMessages[$request->status] ?? 'Trip status updated successfully.';
        
        return redirect()->route('trip-tickets.index')
            ->with('success', $message);
    }
    
    /**
     * Update vehicle mileage when trip is completed
     */
    private function updateVehicleMileage(TripTicket $tripTicket): void
    {
        // Load the itinerary with related models
        $tripTicket->load(['itinerary.vehicle']);
        
        // Check if itinerary and vehicle exist
        if (!$tripTicket->itinerary || !$tripTicket->itinerary->vehicle) {
            return;
        }
        
        // Get the estimated round-trip distance
        $distance = $this->estimateDistanceFromCLSU($tripTicket->itinerary->destination ?? '');
        
        // If we have a distance, update the vehicle's mileage
        if ($distance !== null) {
            $vehicle = $tripTicket->itinerary->vehicle;
            $vehicle->mileage += $distance;
            $vehicle->save();
        }
    }
    
    /**
     * Create vehicle travel history record from completed trip ticket
     */
    private function createVehicleTravelHistory(TripTicket $tripTicket): void
    {
        // Load the itinerary with related models
        $tripTicket->load(['itinerary.vehicle', 'itinerary.driver', 'itinerary.travelOrder.employee']);
        
        // Check if itinerary exists
        if (!$tripTicket->itinerary) {
            return;
        }
        
        // Check if a travel history record already exists for this trip ticket
        $existingRecord = \App\Models\VehicleTravelHistory::where('trip_ticket_id', $tripTicket->id)->first();
        if ($existingRecord) {
            return; // Prevent duplicate records
        }
        
        // Prepare data for vehicle travel history
        $data = [
            'trip_ticket_id' => $tripTicket->id,
            'vehicle_id' => $tripTicket->itinerary->vehicle_id,
            'driver_id' => $tripTicket->itinerary->driver_id,
            'head_of_party' => $tripTicket->head_of_party,
            'destination' => $tripTicket->itinerary->destination ?? 'N/A',
            'departure_date' => $tripTicket->itinerary->date_from ?? now(),
            'departure_time' => $tripTicket->itinerary->departure_time,
            'arrival_date' => $tripTicket->itinerary->date_to,
            'arrival_time' => $tripTicket->itinerary->departure_time, // Using departure time as default
            'distance_km' => $this->estimateDistanceFromCLSU($tripTicket->itinerary->destination ?? ''),
            'remarks' => 'Auto-generated from completed trip ticket',
        ];
        
        // Create the vehicle travel history record
        \App\Models\VehicleTravelHistory::create($data);
    }
    
    /**
     * Estimate round-trip distance from CLSU to destination using Google Maps API
     */
    private function estimateDistanceFromCLSU(string $destination): float|null
    {
        if (empty($destination)) {
            return null;
        }
        
        // Use OSRM/OpenStreetMap for accurate distance calculation
        $distanceService = new DistanceEstimationService();
        
        // CLSU is located in Science City of Muñoz, Nueva Ecija, Philippines
        $origin = 'Central Luzon State University, Science City of Muñoz, Nueva Ecija, Philippines';
        
        // Get round-trip distance (OSRM service handles the calculation)
        $roundTripDistance = $distanceService->getRoundTripDistance($origin, $destination);
        
        return $roundTripDistance;
    }
    
    /**
     * Remove the specified trip ticket from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $tripTicket = TripTicket::findOrFail($id);
        $tripTicket->delete();
        
        return redirect()->route('trip-tickets.index')
            ->with('success', 'Trip ticket deleted successfully.');
    }
    
    /**
     * Generate PDF for trip ticket
     */
    public function generatePDF($id): Response
    {
        // Load TripTicket with relations
        $ticket = TripTicket::with(['itinerary.driver', 'itinerary.vehicle', 'itinerary.travelOrder'])->findOrFail($id);
        
        // Path to Excel template
        $templatePath = public_path('templates/trip_ticket_template.xlsx');
        if (!file_exists($templatePath)) {
            return response()->json(['error' => 'Trip Ticket template not found.'], 500);
        }
        
        // Load Excel template
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        // Get ticket data
        $driverName = $ticket->itinerary && $ticket->itinerary->driver ? 
                     $ticket->itinerary->driver->first_name . ' ' . $ticket->itinerary->driver->last_name : 'N/A';
        $vehicleModel = $ticket->itinerary && $ticket->itinerary->vehicle ? $ticket->itinerary->vehicle->model : '';
        $vehiclePlate = $ticket->itinerary && $ticket->itinerary->vehicle ? $ticket->itinerary->vehicle->plate_number : '';
        $departureTime = optional($ticket->itinerary)->departure_time ? Carbon::parse($ticket->itinerary->departure_time)->format('h:i A') : '';
        $dateFrom = optional($ticket->itinerary)->date_from ? Carbon::parse($ticket->itinerary->date_from)->format('F d, Y') : '';
        $dateTo = optional($ticket->itinerary)->date_to ? Carbon::parse($ticket->itinerary->date_to)->format('F d, Y') : '';
        $dateDisplay = ($dateFrom && $dateTo && $dateFrom === $dateTo) ? "only" : $dateTo;
        $officerName = 'N/A'; // Officer information not directly available in current model structure
        $officerPosition = 'N/A'; // Officer information not directly available in current model structure
        
        $headPassenger = $ticket->head_of_party ?? 'N/A';
        
        // Get from itinerary
        $destinationsText = optional($ticket->itinerary)->destination ?? 'N/A';
        $purposesText = optional($ticket->itinerary)->purpose ?? 'N/A';
        
        // Find the VP of the Office of the Vice President for Administration for G27 and G28
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
            $officerName = $vp->first_name . ' ' . $vp->last_name;
            $officerPosition = $vp->position ? $vp->position->position_name : 'Vice President for Administration';
        } else {
            $officerName = 'Office of the Vice President for Administration';
            $officerPosition = 'Vice President for Administration';
        }
        
        // Fill Excel cells
        $sheet->setCellValue('B9', $departureTime);
        $sheet->setCellValue('B10', $dateFrom);
        $sheet->setCellValue('B11', $dateDisplay);
        $sheet->setCellValue('H9', $ticket->ticket_number ?? '');
        $sheet->setCellValue('A14', $headPassenger);
        
        // Add passengers to cells D10-D17 (excluding head of party)
        $passengers = $ticket->passengers ?? [];
        $headOfParty = $ticket->head_of_party ?? '';
        
        // Filter out the head of party from passengers
        $regularPassengers = array_filter($passengers, function($passenger) use ($headOfParty) {
            return trim($passenger) !== trim($headOfParty);
        });
        
        // Limit to 8 passengers for cells D10-D17
        $regularPassengers = array_slice($regularPassengers, 0, 8);
        
        // Assign passengers to cells D10 through D17
        $passengerCells = ['D10', 'D11', 'D12', 'D13', 'D14', 'D15', 'D16', 'D17'];
        foreach ($passengerCells as $index => $cell) {
            if (isset($regularPassengers[$index])) {
                $sheet->setCellValue($cell, $regularPassengers[$index]);
            } else {
                $sheet->setCellValue($cell, ''); // Clear the cell if no passenger
            }
        }
        
        $sheet->setCellValue('B19', $destinationsText);
        $sheet->setCellValue('B21', $purposesText);
        $sheet->setCellValue('C21', $vehicleModel);
        $sheet->setCellValue('C22', $vehiclePlate);
        $sheet->setCellValue('C23', $driverName);
        $sheet->setCellValue('C25', $vehicleModel);
        $sheet->setCellValue('C26', $vehiclePlate);
        $sheet->setCellValue('C27', $driverName);
        $sheet->setCellValue('G53', $driverName);
        $sheet->setCellValue('G62', $headPassenger);
        $sheet->setCellValue('G27', $officerName);
        $sheet->setCellValue('G28', $officerPosition);
        
        // Add approval stamp in G25 if trip ticket is approved
        if ($ticket->status === 'Approved' && $ticket->updated_at) {
            $approvedDate = Carbon::parse($ticket->updated_at)->format('M j, Y g:i A');
            $sheet->setCellValue('G25', "APPROVED {$approvedDate}");
        }
        
        // Save Excel temporarily for conversion
        $excelTemp = tempnam(sys_get_temp_dir(), 'trip_ticket_') . '.xlsx';
        IOFactory::createWriter($spreadsheet, 'Xlsx')->save($excelTemp);
        
        // Check if ConvertAPI is configured
        $convertApiSecret = config('services.convertapi.secret');
        
        if (!empty($convertApiSecret)) {
            try {
                // Use ConvertAPI to convert Excel to PDF
                ConvertApi::setApiCredentials($convertApiSecret);
                $result = ConvertApi::convert('pdf', [
                    'File' => $excelTemp,
                ], 'xlsx');
                
                $pdfPath = tempnam(sys_get_temp_dir(), 'trip_ticket_pdf_') . '.pdf';
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
            $tempPdfPath = storage_path('app/temp_trip_ticket_' . $ticket->id . '.pdf');
            
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
            ->header('Content-Disposition', 'inline; filename="Trip_Ticket_' . $ticket->ticket_number . '.pdf"');
    }
}