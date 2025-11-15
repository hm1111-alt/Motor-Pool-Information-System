<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;

class TravelOrderController extends Controller
{
    /**
     * Display a listing of the travel orders with tabs for pending, approved, and cancelled.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employeeId = $user->employee->id ?? null;
        
        // Get the active tab from the request, default to 'pending'
        $activeTab = $request->get('tab', 'pending');
        
        // Get search query
        $search = $request->get('search');
        
        // Build the query
        $query = TravelOrder::where('employee_id', $employeeId);
        
        // Apply search filter if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('destination', 'like', '%' . $search . '%')
                  ->orWhere('purpose', 'like', '%' . $search . '%')
                  ->orWhere('date_from', 'like', '%' . $search . '%')
                  ->orWhere('date_to', 'like', '%' . $search . '%')
                  ->orWhere('departure_time', 'like', '%' . $search . '%');
            });
        }
        
        // Apply status filter based on the active tab
        switch ($activeTab) {
            case 'pending':
                $query->where(function ($q) {
                    $q->where(function ($q) {
                        $q->whereNull('divisionhead_approved')
                          ->whereNull('vp_approved');
                    })->orWhere(function ($q) {
                        $q->where('divisionhead_approved', 1)
                          ->whereNull('vp_approved');
                    })->orWhere(function ($q) {
                        $q->where('divisionhead_approved', 1)
                          ->where('vp_approved', 0);
                    });
                });
                break;
                
            case 'approved':
                $query->where('divisionhead_approved', 1)
                      ->where('vp_approved', 1);
                break;
                
            case 'cancelled':
                $query->where(function ($q) {
                    $q->where(function ($q) {
                        $q->where('divisionhead_approved', 0)
                          ->where('vp_approved', 0);
                    })->orWhere(function ($q) {
                        $q->where('divisionhead_approved', 0)
                          ->whereNull('vp_approved');
                    });
                });
                break;
        }
        
        // Paginate the results (10 per page)
        $travelOrders = $query->orderBy('created_at', 'desc')->paginate(10)->appends(['tab' => $activeTab, 'search' => $search]);
        
        return view('travel-orders.index', compact('travelOrders', 'activeTab', 'search'));
    }

    /**
     * Show the form for creating a new travel order.
     */
    public function create(): View
    {
        return view('travel-orders.create');
    }

    /**
     * Store a newly created travel order in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request data
        $validatedData = $request->validate([
            'purpose' => 'required|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'destination' => 'required|string|max:255',
            'departure_time' => 'required|date_format:H:i',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Create the travel order
        $travelOrder = new TravelOrder();
        $travelOrder->employee_id = $user->employee->id ?? null;
        $travelOrder->purpose = $validatedData['purpose'];
        $travelOrder->date_from = $validatedData['date_from'];
        $travelOrder->date_to = $validatedData['date_to'];
        $travelOrder->destination = $validatedData['destination'];
        $travelOrder->departure_time = $validatedData['departure_time'];
        $travelOrder->status = 'Not yet Approved'; // Default status
        $travelOrder->save();

        // Redirect to the travel orders index page with success message in session
        return redirect()->route('travel-orders.index', ['tab' => 'pending'])->with('success', 'Travel order created successfully!');
    }

    /**
     * Show the form for editing the specified travel order.
     */
    public function edit(TravelOrder $travelOrder): View
    {
        // Ensure the travel order belongs to the authenticated user
        $user = Auth::user();
        if ($travelOrder->employee_id !== $user->employee->id) {
            abort(403);
        }

        return view('travel-orders.edit', compact('travelOrder'));
    }

    /**
     * Update the specified travel order in storage.
     */
    public function update(Request $request, TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the travel order belongs to the authenticated user
        $user = Auth::user();
        if ($travelOrder->employee_id !== $user->employee->id) {
            abort(403);
        }

        // Validate the request data
        $validatedData = $request->validate([
            'purpose' => 'required|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'destination' => 'required|string|max:255',
            'departure_time' => 'required|date_format:H:i',
        ]);

        // Update the travel order
        $travelOrder->purpose = $validatedData['purpose'];
        $travelOrder->date_from = $validatedData['date_from'];
        $travelOrder->date_to = $validatedData['date_to'];
        $travelOrder->destination = $validatedData['destination'];
        $travelOrder->departure_time = $validatedData['departure_time'];
        $travelOrder->save();

        // Redirect to the travel orders index page with success message in session
        return redirect()->route('travel-orders.index', ['tab' => 'pending'])->with('success', 'Travel order updated successfully!');
    }

    /**
     * Remove the specified travel order from storage.
     */
    public function destroy(TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the travel order belongs to the authenticated user
        $user = Auth::user();
        if ($travelOrder->employee_id !== $user->employee->id) {
            abort(403);
        }

        // Delete the travel order
        $travelOrder->delete();

        // Redirect back with a success message in session
        return redirect()->route('travel-orders.index', ['tab' => 'pending'])->with('success', 'Travel order deleted successfully!');
    }
}