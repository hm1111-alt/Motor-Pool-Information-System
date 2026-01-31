<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Driver;
use App\Models\Itinerary;
use App\Models\TripTicket;

class DriverDashboardController extends Controller
{
    /**
     * Display the driver dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Debug: Log user information
        \Log::info('Driver Dashboard Access', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role
        ]);
        
        // Get the driver record for the authenticated user
        $driver = Driver::where('user_id', $user->id)->first();
        
        // Debug: Log driver lookup result
        \Log::info('Driver Lookup Result', [
            'user_id' => $user->id,
            'driver_found' => $driver ? 'Yes' : 'No',
            'driver_id' => $driver ? $driver->id : null
        ]);
        
        // Instead of redirecting, pass null driver and handle in view
        if (!$driver) {
            $driver = null;
            $itineraries = collect(); // Empty collection
            $tripTickets = collect(); // Empty collection
            $calendarEvents = []; // Empty array
            
            return view('dashboards.driver', compact(
                'driver', 
                'itineraries', 
                'tripTickets', 
                'calendarEvents'
            ))->with('error', 'No driver record found for your account. Please contact administrator.');
        }
        
        // Get driver's itineraries (assigned to this driver) - using date_from
        $itineraries = Itinerary::with(['vehicle', 'travelOrder'])
            ->where('driver_id', $driver->id)
            ->orderBy('date_from', 'desc')
            ->paginate(10, ['*'], 'itineraries_page');
        
        // Get driver's trip tickets
        $tripTickets = TripTicket::with(['itinerary.vehicle', 'itinerary.travelOrder'])
            ->whereHas('itinerary', function ($query) use ($driver) {
                $query->where('driver_id', $driver->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'triptickets_page');
        
        // Get upcoming trips for calendar (next 30 days) - using date_from and date_to
        $upcomingTrips = Itinerary::with(['vehicle', 'travelOrder'])
            ->where('driver_id', $driver->id)
            ->where(function($query) {
                $query->where('date_from', '>=', now())
                      ->orWhere('date_to', '>=', now());
            })
            ->where(function($query) {
                $query->where('date_from', '<=', now()->addDays(30))
                      ->orWhere('date_to', '<=', now()->addDays(30));
            })
            ->orderBy('date_from', 'asc')
            ->get();
        
        // Prepare calendar events data
        $calendarEvents = $this->prepareCalendarEvents($upcomingTrips);
        
        return view('dashboards.driver', compact(
            'driver', 
            'itineraries', 
            'tripTickets', 
            'calendarEvents'
        ));
    }
    
    /**
     * Display driver's itineraries.
     */
    public function itineraries(Request $request): View
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        if (!$driver) {
            return redirect()->route('driver.dashboard')
                ->with('error', 'No driver record found.');
        }
        
        $search = $request->get('search');
        $status = $request->get('status');
        
        $query = Itinerary::with(['vehicle', 'travelOrder'])
            ->where('driver_id', $driver->id)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('purpose', 'LIKE', "%{$search}%")
                      ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                          $vehicleQuery->where('make', 'LIKE', "%{$search}%")
                                      ->orWhere('model', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('date_from', 'desc'); // Changed from date to date_from
        
        $itineraries = $query->paginate(15)->appends($request->except('page'));
        
        return view('drivers.itineraries', compact('itineraries', 'search', 'status', 'driver'));
    }
    
    /**
     * Display driver's trip tickets.
     */
    public function tripTickets(Request $request): View
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        if (!$driver) {
            return redirect()->route('driver.dashboard')
                ->with('error', 'No driver record found.');
        }
        
        $search = $request->get('search');
        $status = $request->get('status');
        
        $query = TripTicket::with(['itinerary.vehicle', 'itinerary.travelOrder'])
            ->whereHas('itinerary', function ($query) use ($driver) {
                $query->where('driver_id', $driver->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where('ticket_number', 'LIKE', "%{$search}%")
                            ->orWhereHas('itinerary', function ($itineraryQuery) use ($search) {
                                $itineraryQuery->where('purpose', 'LIKE', "%{$search}%");
                            });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc');
        
        $tripTickets = $query->paginate(15)->appends($request->except('page'));
        
        return view('drivers.trip-tickets', compact('tripTickets', 'search', 'status', 'driver'));
    }
    
    /**
     * Get calendar events for AJAX requests.
     */
    public function getCalendarEvents(Request $request)
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        if (!$driver) {
            return response()->json(['error' => 'No driver record found.'], 404);
        }
        
        $startDate = $request->get('start');
        $endDate = $request->get('end');
        
        $trips = Itinerary::with(['vehicle', 'travelOrder'])
            ->where('driver_id', $driver->id)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('date_from', [$startDate, $endDate])
                      ->orWhereBetween('date_to', [$startDate, $endDate]);
            })
            ->get();
        
        $events = $this->prepareCalendarEvents($trips);
        
        return response()->json($events);
    }
    
    /**
     * Prepare calendar events data.
     */
    private function prepareCalendarEvents($trips)
    {
        $events = [];
        
        foreach ($trips as $trip) {
            $statusClass = '';
            switch ($trip->status) {
                case 'Approved':
                    $statusClass = 'bg-green-100 text-green-800';
                    break;
                case 'Pending':
                    $statusClass = 'bg-yellow-100 text-yellow-800';
                    break;
                case 'Rejected':
                    $statusClass = 'bg-red-100 text-red-800';
                    break;
                default:
                    $statusClass = 'bg-gray-100 text-gray-800';
            }
            
            // Use date_from for the event date, fallback to date_to if date_from is null
            $eventDate = $trip->date_from ?? $trip->date_to ?? now();
            
            $events[] = [
                'id' => $trip->id,
                'title' => $trip->purpose ?? 'Unnamed Trip',
                'start' => $eventDate->format('Y-m-d'),
                'allDay' => true,
                'extendedProps' => [
                    'vehicle' => $trip->vehicle ? $trip->vehicle->make . ' ' . $trip->vehicle->model : 'No vehicle assigned',
                    'status' => $trip->status,
                    'statusClass' => $statusClass,
                    'destination' => $trip->destination ?? 'Not specified',
                ]
            ];
        }
        
        return $events;
    }
}