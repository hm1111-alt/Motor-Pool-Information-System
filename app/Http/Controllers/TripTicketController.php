<?php

namespace App\Http\Controllers;

use App\Models\TripTicket;
use App\Models\Itinerary;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\TravelOrder;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class TripTicketController extends Controller
{
    /**
     * Display a listing of the trip tickets.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        
        // Get pending trip tickets (status: Pending)
        $pendingQuery = TripTicket::with(['itinerary.driver', 'itinerary.vehicle', 'itinerary.travelOrder'])
            ->where('status', 'Pending');
        
        if ($search) {
            $pendingQuery->whereHas('itinerary.driver', function($q) use ($search) {
                $q->where('first_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $search . '%');
            });
        }
        $pendingTripTickets = $pendingQuery->paginate(10);
        
        // Get ongoing trip tickets (status: Issued)
        $ongoingQuery = TripTicket::with(['itinerary.driver', 'itinerary.vehicle', 'itinerary.travelOrder'])
            ->where('status', 'Issued');
        
        if ($search) {
            $ongoingQuery->whereHas('itinerary.driver', function($q) use ($search) {
                $q->where('first_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $search . '%');
            });
        }
        $ongoingTripTickets = $ongoingQuery->paginate(10);
        
        // Get completed trip tickets (status: Completed or Cancelled)
        $completedQuery = TripTicket::with(['itinerary.driver', 'itinerary.vehicle', 'itinerary.travelOrder'])
            ->whereIn('status', ['Completed', 'Cancelled']);
        
        if ($search) {
            $completedQuery->whereHas('itinerary.driver', function($q) use ($search) {
                $q->where('first_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $search . '%');
            });
        }
        $completedTripTickets = $completedQuery->paginate(10);
        
        return view('trip-tickets.index', compact('pendingTripTickets', 'ongoingTripTickets', 'completedTripTickets', 'search'));
    }

    /**
     * Show the form for creating a new trip ticket.
     */
    public function create(): View
    {
        // Get itineraries that are fully approved (by both unit head and VP) and don't have trip tickets yet
        $itineraries = Itinerary::with(['driver', 'vehicle', 'travelOrder.employee'])
            ->leftJoin('trip_tickets', 'itineraries.id', '=', 'trip_tickets.itinerary_id')
            ->where('itineraries.unit_head_approved', true)
            ->where('itineraries.vp_approved', true)
            ->whereNotNull('itineraries.unit_head_approved_at')
            ->whereNotNull('itineraries.vp_approved_at')
            ->whereNull('trip_tickets.itinerary_id')
            ->select('itineraries.*')
            ->get();
        
        // Get travel orders that don't have itineraries
        $travelOrdersWithoutItinerary = TravelOrder::with(['employee'])
            ->whereDoesntHave('itinerary')
            ->select('travel_orders.*')
            ->get();
        
        return view('trip-tickets.create', compact('itineraries', 'travelOrdersWithoutItinerary'));
    }

    /**
     * Store a newly created trip ticket in storage.
     */
    public function store(Request $request): RedirectResponse
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
        
        $tripTicket->update([
            'status' => $request->status,
        ]);
        
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