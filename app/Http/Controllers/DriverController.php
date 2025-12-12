<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DriverController extends Controller
{
    /**
     * Display a listing of the drivers.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        
        $drivers = Driver::when($search, function ($query, $search) {
                return $query->where('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%")
                            ->orWhere('position', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('drivers.index', compact('drivers', 'search'));
    }

    /**
     * Show the form for creating a new driver.
     */
    public function create(): View
    {
        return view('drivers.create');
    }

    /**
     * Store a newly created driver in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'ext_name' => 'nullable|string|max:50',
            'sex' => 'required|in:Male,Female',
            'contact_number' => 'nullable|string|max:50',
            'position' => 'required|string|max:255',
            'official_station' => 'nullable|string|max:255',
            'availability_status' => 'required|in:Available,Not Available,On Duty,Off Duty',
        ]);

        $data = $request->all();
        
        // Generate full names
        $data['full_name'] = $data['first_name'] . ' ' . $data['last_name'];
        $data['full_name2'] = $data['last_name'] . ', ' . $data['first_name'];

        Driver::create($data);

        return redirect()->route('drivers.index')
            ->with('success', 'Driver created successfully.');
    }

    /**
     * Display the specified driver.
     */
    public function show(Driver $driver): View
    {
        return view('drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified driver.
     */
    public function edit(Driver $driver): View
    {
        return view('drivers.edit', compact('driver'));
    }

    /**
     * Update the specified driver in storage.
     */
    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'ext_name' => 'nullable|string|max:50',
            'sex' => 'required|in:Male,Female',
            'contact_number' => 'nullable|string|max:50',
            'position' => 'required|string|max:255',
            'official_station' => 'nullable|string|max:255',
            'availability_status' => 'required|in:Available,Not Available,On Duty,Off Duty',
        ]);

        $data = $request->all();
        
        // Generate full names
        $data['full_name'] = $data['first_name'] . ' ' . $data['last_name'];
        $data['full_name2'] = $data['last_name'] . ', ' . $data['first_name'];

        $driver->update($data);

        return redirect()->route('drivers.index')
            ->with('success', 'Driver updated successfully.');
    }

    /**
     * Remove the specified driver from storage.
     */
    public function destroy(Driver $driver): RedirectResponse
    {
        $driver->delete();

        return redirect()->route('drivers.index')
            ->with('success', 'Driver deleted successfully.');
    }
}