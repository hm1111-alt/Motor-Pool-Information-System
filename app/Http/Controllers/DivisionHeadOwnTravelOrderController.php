<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use App\Models\EmpPosition;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class DivisionHeadOwnTravelOrderController extends Controller
{
    /**
     * Display a listing of the travel orders.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');

        // Get search term if provided
        $search = $request->get('search', '');

        // Get all travel orders for the current employee
        $query = TravelOrder::where('employee_id', $employee->id);

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

        // Apply tab-specific filtering
        switch ($tab) {
            case 'approved':
                // For division heads, approved means both VP and President have approved
                $query->where('vp_approved', true)
                      ->where('president_approved', true);
                break;
            case 'cancelled':
                // Cancelled if either VP or President rejected
                $query->where(function($q) {
                    $q->where('vp_approved', false)
                      ->orWhere('president_approved', false);
                });
                break;
            case 'pending':
            default:
                // For division heads, pending means not yet fully approved (either waiting for VP or President approval)
                $query->where(function($q) {
                    $q->whereNull('vp_approved')
                      ->orWhere(function($subQ) {
                          $subQ->where('vp_approved', true)
                               ->whereNull('president_approved');
                      });
                });
                break;
        }

        // Get paginated results with position information
        $travelOrders = $query->with('position', 'employee')->orderBy('created_at', 'desc')->paginate(10)->appends($request->except('page'));

        return view('travel-orders.index', compact('travelOrders', 'tab', 'search'));
    }

    /**
     * Show the form for creating a new travel order.
     */
    public function create(): View
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Get all positions for this employee
        $positions = $employee->positions;

        return view('travel-orders.create', compact('positions'));
    }

    /**
     * Store a newly created travel order in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Validate that the user is a division head
        if (!$employee->is_divisionhead) {
            return Redirect::route('dashboard')
                ->with('error', 'Only division heads can create their own travel orders.');
        }

        $request->validate([
            'emp_position_id' => 'required|exists:emp_positions,id',
            'destination' => 'required|string|max:255',
            'date_from' => 'required|date|before_or_equal:date_to',
            'date_to' => 'required|date|after_or_equal:date_from',
            'departure_time' => 'nullable|date_format:H:i',
            'purpose' => 'required|string|max:500',
        ]);

        // Create the travel order with initial status as pending
        $travelOrder = TravelOrder::create([
            'employee_id' => $employee->id,
            'emp_position_id' => $request->emp_position_id,
            'destination' => $request->destination,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'departure_time' => $request->departure_time,
            'purpose' => $request->purpose,
            'status' => 'pending', // Initially pending
        ]);

        return Redirect::route('division-head-own-travel-orders.index')
            ->with('success', 'Travel order created successfully. Awaiting VP approval.');
    }

    /**
     * Display the specified travel order.
     */
    public function show(TravelOrder $travelOrder): View
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Ensure the logged-in user is the owner of the travel order
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }

        // Ensure the user is a division head
        if (!$employee->is_divisionhead) {
            return Redirect::route('dashboard')
                ->with('error', 'Access denied. Only division heads can view their own travel orders.');
        }

        return view('travel-orders.show', compact('travelOrder'));
    }

    /**
     * Show the form for editing the specified travel order.
     */
    public function edit(TravelOrder $travelOrder): View
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Ensure the logged-in user is the owner of the travel order
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }

        // Ensure the user is a division head
        if (!$employee->is_divisionhead) {
            return Redirect::route('dashboard')
                ->with('error', 'Access denied. Only division heads can edit their own travel orders.');
        }

        // Check if the travel order can be edited (not yet approved by anyone)
        if (!is_null($travelOrder->vp_approved)) {
            return Redirect::back()
                ->with('error', 'Cannot edit travel order after VP approval has started.');
        }

        $positions = $employee->positions;

        return view('travel-orders.edit', compact('travelOrder', 'positions'));
    }

    /**
     * Update the specified travel order in storage.
     */
    public function update(Request $request, TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Ensure the logged-in user is the owner of the travel order
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }

        // Ensure the user is a division head
        if (!$employee->is_divisionhead) {
            return Redirect::route('dashboard')
                ->with('error', 'Access denied. Only division heads can update their own travel orders.');
        }

        // Check if the travel order can be updated (not yet approved by anyone)
        if (!is_null($travelOrder->vp_approved)) {
            return Redirect::back()
                ->with('error', 'Cannot update travel order after VP approval has started.');
        }

        $request->validate([
            'emp_position_id' => 'required|exists:emp_positions,id',
            'destination' => 'required|string|max:255',
            'date_from' => 'required|date|before_or_equal:date_to',
            'date_to' => 'required|date|after_or_equal:date_from',
            'departure_time' => 'nullable|date_format:H:i',
            'purpose' => 'required|string|max:500',
        ]);

        $travelOrder->update([
            'emp_position_id' => $request->emp_position_id,
            'destination' => $request->destination,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'departure_time' => $request->departure_time,
            'purpose' => $request->purpose,
        ]);

        return Redirect::route('division-head-own-travel-orders.index')
            ->with('success', 'Travel order updated successfully.');
    }

    /**
     * Remove the specified travel order from storage.
     */
    public function destroy(TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Ensure the logged-in user is the owner of the travel order
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }

        // Ensure the user is a division head
        if (!$employee->is_divisionhead) {
            return Redirect::route('dashboard')
                ->with('error', 'Access denied. Only division heads can delete their own travel orders.');
        }

        // Check if the travel order can be deleted (not yet approved by anyone)
        if (!is_null($travelOrder->vp_approved)) {
            return Redirect::back()
                ->with('error', 'Cannot delete travel order after VP approval has started.');
        }

        $travelOrder->delete();

        return Redirect::route('division-head-own-travel-orders.index')
            ->with('success', 'Travel order deleted successfully.');
    }
}