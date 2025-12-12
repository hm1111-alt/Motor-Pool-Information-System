<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    /**
     * Display a listing of the vehicles.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        
        $vehicles = Vehicle::when($search, function ($query, $search) {
                return $query->where('plate_number', 'LIKE', "%{$search}%")
                            ->orWhere('model', 'LIKE', "%{$search}%")
                            ->orWhere('type', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('vehicles.index', compact('vehicles', 'search'));
    }

    /**
     * Display a simple listing of the vehicles.
     */
    public function simpleIndex(Request $request): View
    {
        $search = $request->get('search');
        
        $vehicles = Vehicle::when($search, function ($query, $search) {
                return $query->where('plate_number', 'LIKE', "%{$search}%")
                            ->orWhere('model', 'LIKE', "%{$search}%")
                            ->orWhere('type', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('vehicles.simple-index', compact('vehicles', 'search'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create(): View
    {
        return view('vehicles.create');
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'plate_number' => 'required|string|max:50|unique:vehicle,plate_number',
            'model' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'seating_capacity' => 'required|integer|min:1',
            'mileage' => 'required|integer|min:0',
            'status' => 'required|in:Available,Not Available,Active,Under Maintenance',
        ]);

        $data = $request->except('picture');
        
        if ($request->hasFile('picture')) {
            $data['picture'] = $request->file('picture')->store('vehicles', 'public');
        }

        Vehicle::create($data);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified vehicle.
     */
    public function show(Vehicle $vehicle): View
    {
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(Vehicle $vehicle): View
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        // Debugging: Log the vehicle ID and plate number
        \Log::info('Updating vehicle ID: ' . $vehicle->id . ', Plate Number: ' . $vehicle->plate_number);
        \Log::info('Request data: ', $request->all());
        
        // Get the current plate number from the request
        $newPlateNumber = $request->input('plate_number');
        \Log::info('New plate number: ' . $newPlateNumber);
        \Log::info('Current vehicle plate number: ' . $vehicle->plate_number);
        
        $request->validate([
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'plate_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('vehicle', 'plate_number')->ignore($vehicle->id)
            ],
            'model' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'seating_capacity' => 'required|integer|min:1',
            'mileage' => 'required|integer|min:0',
            'status' => 'required|in:Available,Not Available,Active,Under Maintenance',
        ]);

        $data = $request->except('picture');
        
        if ($request->hasFile('picture')) {
            // Delete old picture if exists
            if ($vehicle->picture) {
                Storage::disk('public')->delete($vehicle->picture);
            }
            $data['picture'] = $request->file('picture')->store('vehicles', 'public');
        }

        $vehicle->update($data);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        // Delete picture if exists
        if ($vehicle->picture) {
            Storage::disk('public')->delete($vehicle->picture);
        }
        
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}