<?php

namespace App\Http\Controllers;

use App\Models\TripTicket;
use App\Models\Itinerary;
use App\Models\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        
        // Get ongoing trip tickets (status: Issued)
        $ongoingQuery = TripTicket::with(['itinerary.driver', 'itinerary.vehicle', 'itinerary.travelOrder'])
            ->where('status', 'Issued');
        
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
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'itinerary_id' => 'required|exists:itineraries,id',
        ]);
        
        // Process passengers if provided
        $passengers = [];
        if ($request->has('passenger_names') && is_array($request->passenger_names)) {
            $passengers = array_filter($request->passenger_names);
        }
        
        // Get the head of party
        $headOfParty = $request->input('head_of_party', null);
        
        // Create the trip ticket
        $tripTicket = TripTicket::create([
            'itinerary_id' => $request->itinerary_id,
            'status' => 'Pending',
            'passengers' => $passengers,
            'head_of_party' => $headOfParty,
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
            'status' => 'required|in:Pending,Issued,Completed,Cancelled,Archived',
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
            'Issued' => 'Trip started successfully.',
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
}