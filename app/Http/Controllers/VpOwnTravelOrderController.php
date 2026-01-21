<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;

class VpOwnTravelOrderController extends Controller
{
    /**
     * Display a listing of the VP's own travel orders.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get travel orders for this employee based on the selected tab
        $query = TravelOrder::where('employee_id', $employee->id);
        
        switch ($tab) {
            case 'approved':
                // For VPs, approved means president has approved
                $query->where('president_approved', true);
                break;
            case 'cancelled':
                // For VPs, cancelled means president rejected
                $query->where('president_approved', false);
                break;
            case 'pending':
            default:
                // For VPs, pending means either not yet approved by president
                $query->where('president_approved', null);
                break;
        }
        
        $travelOrders = $query->with('position')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('travel-orders.index', compact('travelOrders', 'tab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        if (!$employee) {
            abort(403, 'You do not have an employee record.');
        }
        
        // Get all positions for the employee
        $positions = $employee->positions()->with(['office', 'division', 'unit', 'subunit', 'class'])->get();
        
        return view('travel-orders.create', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'destination' => 'required|string|max:255',
            'date_from' => 'required|date|before_or_equal:date_to',
            'date_to' => 'required|date|after_or_equal=date_from',
            'departure_time' => 'nullable|date_format:H:i',
            'purpose' => 'required|string|max:500',
            'position_id' => 'required|exists:emp_positions,id', // Add validation for position selection
        ]);
        
        $user = Auth::user();
        $employee = $user->employee;
                
        // Check if employee exists
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an employee record. Please contact your administrator to set up your employee profile.');
        }
        
        // Verify that the selected position belongs to the employee
        $position = $employee->positions()->where('id', $request->position_id)->first();
        if (!$position) {
            return redirect()->back()
                ->with('error', 'Invalid position selected.')
                ->withInput();
        }
        
        // Create the travel order
        $travelOrder = TravelOrder::create([
            'employee_id' => $employee->id,
            'emp_position_id' => $request->position_id, // Associate with the selected position
            'destination' => $request->destination,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'departure_time' => $request->departure_time,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);
        
        return redirect()->route('vp.travel-orders.index', ['tab' => 'pending'])
            ->with('success', 'Travel order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TravelOrder $travelOrder): View
    {
        // Ensure the VP can only view their own travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        return view('travel-orders.show', compact('travelOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TravelOrder $travelOrder): View
    {
        // Ensure the VP can only edit their own pending travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow editing if not yet approved by president
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        return view('travel-orders.edit', compact('travelOrder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the VP can only update their own pending travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow updating if not yet approved by president
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        $request->validate([
            'destination' => 'required|string|max:255',
            'date_from' => 'required|date|before_or_equal:date_to',
            'date_to' => 'required|date|after_or_equal:date_from',
            'departure_time' => 'nullable|date_format:H:i',
            'purpose' => 'required|string|max:500',
        ]);

        $travelOrder->update([
            'destination' => $request->destination,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'departure_time' => $request->departure_time,
            'purpose' => $request->purpose,
        ]);

        return redirect()->route('vp.travel-orders.index', ['tab' => 'pending'])
            ->with('success', 'Travel order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the VP can only delete their own pending travel orders
        $user = Auth::user();
        $employee = $user->employee;
        
        if ($travelOrder->employee_id !== $employee->id) {
            abort(403);
        }
        
        // Only allow deleting if not yet approved by president
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        $travelOrder->delete();

        return redirect()->route('vp.travel-orders.index', ['tab' => 'pending'])
            ->with('success', 'Travel order deleted successfully.');
    }
}