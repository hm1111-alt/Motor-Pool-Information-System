<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the itineraries.
     */
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'pending'); // Default to pending tab
        
        // Base query
        $query = Itinerary::with(['travelOrder', 'vehicle', 'driver']);
        
        // Apply tab filtering
        switch ($tab) {
            case 'approved':
                $query->where('unit_head_approved', true)
                      ->where('vp_approved', true)
                      ->whereNotNull('vp_approved_at');
                break;
            case 'cancelled':
                $query->where(function($q) {
                    $q->where('unit_head_approved', false)
                      ->whereNotNull('unit_head_approved_at')
                      ->orWhere(function($q2) {
                          $q2->where('unit_head_approved', true)
                             ->where('vp_approved', false)
                             ->whereNotNull('vp_approved_at');
                      });
                });
                break;
            case 'pending':
            default:
                $query->where(function($q) {
                    $q->where('unit_head_approved', false)
                      ->whereNull('unit_head_approved_at')
                      ->orWhere(function($q2) {
                          $q2->where('unit_head_approved', true)
                             ->where('vp_approved', false)
                             ->whereNull('vp_approved_at');
                      });
                });
                break;
        }
        
        $itineraries = $query->orderBy('date_from', 'desc')
            ->orderBy('departure_time', 'asc')
            ->paginate(10);
        
        // Get counts for each tab
        $pendingCount = Itinerary::where(function($q) {
            $q->where('unit_head_approved', false)
              ->whereNull('unit_head_approved_at')
              ->orWhere(function($q2) {
                  $q2->where('unit_head_approved', true)
                     ->where('vp_approved', false)
                     ->whereNull('vp_approved_at');
              });
        })->count();
        
        $approvedCount = Itinerary::where('unit_head_approved', true)
            ->where('vp_approved', true)
            ->whereNotNull('vp_approved_at')
            ->count();
        
        $cancelledCount = Itinerary::where(function($q) {
            $q->where('unit_head_approved', false)
              ->whereNotNull('unit_head_approved_at')
              ->orWhere(function($q2) {
                  $q2->where('unit_head_approved', true)
                     ->where('vp_approved', false)
                     ->whereNotNull('vp_approved_at');
              });
        })->count();

        return view('itineraries.index', compact('itineraries', 'tab', 'pendingCount', 'approvedCount', 'cancelledCount'));
    }

    /**
     * Show the form for creating a new itinerary.
     */
    public function create(): View
    {
        $travelOrders = Schema::hasTable('travel_orders') ? TravelOrder::all() : collect([]);
        $vehicles = Schema::hasTable('vehicles') ? Vehicle::all() : collect([]);
        $drivers = Schema::hasTable('drivers') ? Driver::all() : collect([]);
        
        return view('itineraries.create', compact('travelOrders', 'vehicles', 'drivers'));
    }

    /**
     * Store a newly created itinerary in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validationRules = [
            'travel_order_id' => 'nullable',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string|max:500',
            'departure_time' => 'required',
        ];

        // Conditionally add foreign key validations if tables exist
        if (Schema::hasTable('vehicles')) {
            $validationRules['vehicle_id'] = 'nullable|exists:vehicles,id';
        }

        if (Schema::hasTable('drivers')) {
            $validationRules['driver_id'] = 'nullable|exists:drivers,id';
        }

        if (Schema::hasTable('travel_orders')) {
            $validationRules['travel_order_id'] = 'nullable|exists:travel_orders,id';
        }

        $request->validate($validationRules);

        Itinerary::create([
            'travel_order_id' => $request->travel_order_id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'destination' => $request->destination,
            'purpose' => $request->purpose,
            'departure_time' => $request->departure_time,
            'status' => 'Not yet Approved', // Default status
            'vehicle_id' => $request->vehicle_id,
            'driver_id' => $request->driver_id,
            'unit_head_approved' => false,
            'vp_approved' => false,
        ]);

        return redirect()->route('itinerary.index')
            ->with('success', 'Itinerary created successfully.');
    }

    /**
     * Display the specified itinerary.
     */
    public function show(Itinerary $itinerary): View
    {
        $itinerary->load(['travelOrder', 'vehicle', 'driver']);
        
        return view('itineraries.show', compact('itinerary'));
    }

    /**
     * Show the form for editing the specified itinerary.
     */
    public function edit(Itinerary $itinerary): View
    {
        $travelOrders = Schema::hasTable('travel_orders') ? TravelOrder::all() : collect([]);
        $vehicles = Schema::hasTable('vehicles') ? Vehicle::all() : collect([]);
        $drivers = Schema::hasTable('drivers') ? Driver::all() : collect([]);
        
        return view('itineraries.edit', compact('itinerary', 'travelOrders', 'vehicles', 'drivers'));
    }

    /**
     * Update the specified itinerary in storage.
     */
    public function update(Request $request, Itinerary $itinerary): RedirectResponse
    {
        $validationRules = [
            'travel_order_id' => 'nullable',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string|max:500',
            'departure_time' => 'required',
        ];

        // Conditionally add foreign key validations if tables exist
        if (Schema::hasTable('vehicles')) {
            $validationRules['vehicle_id'] = 'nullable|exists:vehicles,id';
        }

        if (Schema::hasTable('drivers')) {
            $validationRules['driver_id'] = 'nullable|exists:drivers,id';
        }

        if (Schema::hasTable('travel_orders')) {
            $validationRules['travel_order_id'] = 'nullable|exists:travel_orders,id';
        }

        $request->validate($validationRules);

        $itinerary->update([
            'travel_order_id' => $request->travel_order_id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'destination' => $request->destination,
            'purpose' => $request->purpose,
            'departure_time' => $request->departure_time,
            'status' => $request->status ?? 'Not yet Approved',
            'vehicle_id' => $request->vehicle_id,
            'driver_id' => $request->driver_id,
        ]);

        return redirect()->route('itinerary.index')
            ->with('success', 'Itinerary updated successfully.');
    }

    /**
     * Remove the specified itinerary from storage.
     */
    public function destroy(Itinerary $itinerary): RedirectResponse
    {
        $itinerary->delete();

        return redirect()->route('itinerary.index')
            ->with('success', 'Itinerary deleted successfully.');
    }
    
    /**
     * Get the creator of an itinerary (from the associated travel order).
     */
    public function getCreator($id): \Illuminate\Http\JsonResponse
    {
        $itinerary = Itinerary::with('travelOrder.employee')->find($id);
        
        if (!$itinerary) {
            return response()->json(['error' => 'Itinerary not found'], 404);
        }
        
        $creatorName = '';
        if ($itinerary->travelOrder && $itinerary->travelOrder->employee) {
            $creatorName = $itinerary->travelOrder->employee->first_name . ' ' . $itinerary->travelOrder->employee->last_name;
        }
        
        return response()->json([
            'creator_name' => $creatorName
        ]);
    }
}