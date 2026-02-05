<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\TripTicket;
use App\Models\Employee;
use App\Models\Itinerary;
use App\Models\Vehicle;
use App\Models\Driver;

class MotorpoolAdminController extends Controller
{
    /**
     * Display the motorpool admin dashboard.
     */
    public function dashboard(): View
    {
        // Get counts for dashboard cards
        $itinerariesCount = Itinerary::count();
        $tripTicketsCount = TripTicket::count();
        $vehiclesCount = Vehicle::count();
        $driversCount = Driver::count();
        
        // Get trip status counts
        $pendingCount = TripTicket::where('status', 'Pending')->count();
        $ongoingCount = TripTicket::where('status', 'On-going')->count();
        $completedCount = TripTicket::where('status', 'Completed')->count();
        
        return view('dashboards.motorpool-admin', compact(
            'itinerariesCount',
            'tripTicketsCount', 
            'vehiclesCount',
            'driversCount',
            'pendingCount',
            'ongoingCount',
            'completedCount'
        ));
    }
    
    /**
     * API endpoint for calendar events
     */
    public function calendarEvents(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');
        
        $tripTickets = TripTicket::with([
            'itinerary.driver',
            'itinerary.vehicle'
        ])
        ->whereHas('itinerary', function($query) use ($start, $end) {
            $query->whereBetween('date_from', [$start, $end]);
        })
        ->get()
        ->map(function($ticket) {
            return [
                'id' => $ticket->id,
                'driver_name' => $ticket->itinerary?->driver?->full_name ?? 'No Driver Assigned',
                'itinerary' => [
                    'date_from' => $ticket->itinerary?->date_from,
                    'date_to' => $ticket->itinerary?->date_to,
                    'destination' => $ticket->itinerary?->destination,
                    'vehicle' => [
                        'make' => $ticket->itinerary?->vehicle?->make,
                        'model' => $ticket->itinerary?->vehicle?->model
                    ]
                ],
                'status' => $ticket->status,
                'created_at' => $ticket->created_at
            ];
        });
        
        return response()->json($tripTickets);
    }
    
    /**
     * API endpoint for status counts by month
     */
    public function statusCounts(Request $request)
    {
        $month = $request->query('month');
        
        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $startDate = "$year-$monthNum-01";
            $endDate = date('Y-m-t', strtotime($startDate));
        } else {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        }
        
        $counts = TripTicket::select('status')
            ->whereHas('itinerary', function($query) use ($startDate, $endDate) {
                $query->whereBetween('date_from', [$startDate, $endDate]);
            })
            ->groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->pluck('count', 'status')
            ->toArray();
        
        return response()->json([
            'pending' => $counts['Pending'] ?? 0,
            'ongoing' => $counts['On-going'] ?? 0,
            'completed' => $counts['Completed'] ?? 0
        ]);
    }
    
    /**
     * Display approved travel orders for the motorpool.
     */
    public function approvedTravelOrders(Request $request): View
    {
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Get travel orders that are approved and ready for motorpool processing
        $query = TravelOrder::where('status', 'approved')
            ->with('employee'); // Eager load employee relationship
        
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
        
        // Check if this is an AJAX request for partial updates
        if ($request->ajax() || $request->get('ajax')) {
            $travelOrders = $query->orderBy('created_at', 'desc')->get();
            return view('travel-orders.approvals.partials.motorpool-table-rows', compact('travelOrders'))->render();
        }
        
        $travelOrders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('travel-orders.approvals.motorpool-index', compact('travelOrders', 'search'));
    }
}