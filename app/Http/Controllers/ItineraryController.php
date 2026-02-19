<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $drivers = \App\Models\Driver::where('availability_status', 'Available')->get();
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
        $drivers = \App\Models\Driver::where('availability_status', 'Available')->get();
        $travelOrders = TravelOrder::where('status', 'approved')->get();
        
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
    public function createModal(): View
    {
        $vehicles = \App\Models\Vehicle::whereIn('status', ['Available', 'Active'])->get();
        $drivers = \App\Models\Driver::where('availability_status', 'Available')->get();
        $travelOrders = TravelOrder::where('status', 'approved')->get();
        
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
        $drivers = \App\Models\Driver::where('availability_status', 'Available')->get();
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
}