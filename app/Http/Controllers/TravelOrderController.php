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

        // Redirect back with a success message
        return redirect()->route('travel-orders.create')->with('success', 'Travel order submitted successfully!');
    }
}