<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\Driver;
use App\Models\TripTicket;
use App\Models\Itinerary;

class DashboardController extends Controller
{
    /**
     * Display the appropriate dashboard based on user role
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        // If user is not logged in, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Determine which dashboard to show based on user role
        if ($user->isMotorpoolAdmin()) {
            // Redirect motorpool admin to their specific dashboard
            return redirect()->route('motorpool.dashboard');
        } elseif ($user->isAdmin()) {
            return view('dashboards.admin');
        } elseif ($user->isDriver()) {
            // Load driver data for driver dashboard
            $driver = Driver::where('user_id', $user->id)->first();
            
            if (!$driver) {
                // If no driver record exists, show error on driver dashboard
                return view('dashboards.driver', [
                    'driver' => null,
                    'itineraries' => collect(),
                    'tripTickets' => collect(),
                    'calendarEvents' => []
                ])->with('error', 'No driver record found for your account. Please contact administrator.');
            }
            
            // Load driver's data - using date_from instead of date
            $itineraries = Itinerary::with(['vehicle', 'travelOrder'])
                ->where('driver_id', $driver->id)
                ->orderBy('date_from', 'desc')
                ->paginate(10, ['*'], 'itineraries_page');
            
            $tripTickets = TripTicket::with(['itinerary.vehicle', 'itinerary.travelOrder'])
                ->whereHas('itinerary', function ($query) use ($driver) {
                    $query->where('driver_id', $driver->id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'triptickets_page');
            
            // For calendar events, use date_from (or the first date if date_from is null)
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
            
            $calendarEvents = $this->prepareCalendarEvents($upcomingTrips);
            
            return view('dashboards.driver', compact(
                'driver', 
                'itineraries', 
                'tripTickets', 
                'calendarEvents'
            ));
        } elseif ($user->isEmployee()) {
            // Load the employee relationship
            $user->load('employee');
            
            // Check if employee is a head (division head, VP, or unit head)
            $employee = $user->employee;
            if ($employee) {
                // Determine which dashboard to show based on specific role
                if ($employee->is_president) {
                    return view('dashboards.president');
                } elseif ($employee->is_vp) {
                    return view('dashboards.vp');
                } elseif ($employee->is_divisionhead) {
                    return view('dashboards.divisionhead');
                } elseif ($employee->is_head && !$employee->is_divisionhead && !$employee->is_vp) {
                    return view('dashboards.unithead');
                } elseif ($employee->is_head) {
                    return view('dashboards.head');
                } else {
                    // Regular employee dashboard
                    return view('dashboards.employee');
                }
            } else {
                // Regular employee dashboard
                return view('dashboards.employee');
            }
        }

        // Default dashboard if no role matches
        return view('dashboard');
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