<?php

namespace App\Http\Controllers;

use App\Models\TripTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmployeeTripTicketController extends Controller
{
    /**
     * Display a listing of trip tickets for the employee.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'approved'
        $tab = $request->get('tab', 'approved');
        
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Build query based on the selected tab
        $query = TripTicket::with(['itinerary', 'itinerary.travelOrder.employee']);
        
        // Filter by employee - show trip tickets where the employee is a passenger, head of party, or the original travel order creator
        $query->where(function ($subQuery) use ($employee) {
            // Check if employee is head of party
            $subQuery->where('head_of_party', 'LIKE', "%{$employee->first_name}%{$employee->last_name}%")
                     ->orWhere('head_of_party', 'LIKE', "%{$employee->last_name}%{$employee->first_name}%")
                     // Check if employee is a passenger
                     ->orWhere(function ($passengerQuery) use ($employee) {
                         $passengerQuery->where('passengers', 'LIKE', "%{$employee->first_name}%{$employee->last_name}%")
                                        ->orWhere('passengers', 'LIKE', "%{$employee->last_name}%{$employee->first_name}%");
                     })
                     // Check if employee is the original travel order creator
                     ->orWhereHas('itinerary.travelOrder', function ($travelOrderQuery) use ($employee) {
                         $travelOrderQuery->where('employee_id', $employee->id);
                     });
        });
        
        // Apply search filter if provided
        if ($search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->whereHas('itinerary.travelOrder.employee', function ($empQuery) use ($search) {
                    $empQuery->where('first_name', 'LIKE', "%{$search}%")
                             ->orWhere('last_name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('itinerary', function ($itineraryQuery) use ($search) {
                    $itineraryQuery->where('destination', 'LIKE', "%{$search}%")
                                   ->orWhere('purpose', 'LIKE', "%{$search}%");
                });
            });
        }
        
        switch ($tab) {
            case 'completed':
                // Trip tickets that have been completed
                $query->where('status', 'Completed');
                break;
            case 'cancelled':
                // Cancelled trip tickets
                $query->where('status', 'Cancelled');
                break;
            case 'approved':
            default:
                // Approved trip tickets (Approved status)
                $query->where('status', 'Approved');
                break;
        }
        
        $tripTickets = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('trip-tickets.employee.index', compact('tripTickets', 'tab', 'search'));
    }
    
    /**
     * Display the specified trip ticket for the employee.
     */
    public function show(TripTicket $tripTicket): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if the employee is a passenger or head of party
        $isPassenger = false;
        if ($tripTicket->passengers) {
            if (is_array($tripTicket->passengers)) {
                // If passengers is an array
                foreach ($tripTicket->passengers as $passenger) {
                    if (str_contains($passenger, $employee->first_name) && str_contains($passenger, $employee->last_name)) {
                        $isPassenger = true;
                        break;
                    }
                }
            } else {
                // If passengers is a string (JSON encoded)
                $isPassenger = str_contains($tripTicket->passengers, $employee->first_name) && 
                              str_contains($tripTicket->passengers, $employee->last_name);
            }
        }
        
        $isHeadOfParty = str_contains($tripTicket->head_of_party, $employee->first_name) && 
                        str_contains($tripTicket->head_of_party, $employee->last_name);
        
        // Check if employee is the original travel order creator
        $isTravelOrderCreator = $tripTicket->itinerary->travelOrder->employee_id === $employee->id;
        
        if (!$isPassenger && !$isHeadOfParty && !$isTravelOrderCreator) {
            abort(403);
        }
        
        return view('trip-tickets.employee.show', compact('tripTicket'));
    }
}