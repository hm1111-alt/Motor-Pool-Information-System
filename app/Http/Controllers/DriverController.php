<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    /**
     * Display a listing of the drivers.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        
        $query = Driver::with('user')
            ->when($search, function ($query, $search) {
                return $query->where('firsts_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('position', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc');
        
        $drivers = $query->paginate(10)->appends($request->except('page'));
        
        return view('drivers.index', compact('drivers', 'search'));
    }

    /**
     * Show the form for creating a new driver.
     */
    public function create(): View
    {
        $users = User::orderBy('name')->get();
        return view('drivers.create', compact('users'));
    }

    /**
     * Store a newly created driver in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'firsts_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'required|string|max:20',
            'email' => 'required|email|unique:drivers,email',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string',
            'position' => 'required|string|max:255',
            'official_station' => 'required|string|max:255',
            'availability_status' => 'required|in:Available,Not Available,On Duty,Off Duty',
        ]);

        // Generate full names
        $fullName = $request->firsts_name . ' ' . $request->last_name;
        $fullName2 = $request->firsts_name;
        if ($request->middle_initial) {
            $fullName2 .= ' ' . $request->middle_initial . '.';
        }
        $fullName2 .= ' ' . $request->last_name;

        Driver::create([
            'user_id' => $request->user_id,
            'firsts_name' => $request->firsts_name,
            'middle_initial' => $request->middle_initial,
            'last_name' => $request->last_name,
            'full_name' => $fullName,
            'full_name2' => $fullName2,
            'contact_num' => $request->contact_num,
            'email' => $request->email,
            'password' => $request->password,
            'address' => $request->address,
            'position' => $request->position,
            'official_station' => $request->official_station,
            'availability_status' => $request->availability_status,
        ]);

        return redirect()->route('drivers.index')
            ->with('success', 'Driver created successfully.');
    }

    /**
     * Display the specified driver.
     */
    public function show(Driver $driver): View
    {
        $driver->load('user', 'itineraries.vehicle');
        return view('drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified driver.
     */
    public function edit(Driver $driver): View
    {
        $users = User::orderBy('name')->get();
        return view('drivers.edit', compact('driver', 'users'));
    }

    /**
     * Update the specified driver in storage.
     */
    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'firsts_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'required|string|max:20',
            'email' => 'required|email|unique:drivers,email,' . $driver->id,
            'address' => 'required|string',
            'position' => 'required|string|max:255',
            'official_station' => 'required|string|max:255',
            'availability_status' => 'required|in:Available,Not Available,On Duty,Off Duty',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Generate full names
        $fullName = $request->firsts_name . ' ' . $request->last_name;
        $fullName2 = $request->firsts_name;
        if ($request->middle_initial) {
            $fullName2 .= ' ' . $request->middle_initial . '.';
        }
        $fullName2 .= ' ' . $request->last_name;

        $data = [
            'user_id' => $request->user_id,
            'firsts_name' => $request->firsts_name,
            'middle_initial' => $request->middle_initial,
            'last_name' => $request->last_name,
            'full_name' => $fullName,
            'full_name2' => $fullName2,
            'contact_num' => $request->contact_num,
            'email' => $request->email,
            'address' => $request->address,
            'position' => $request->position,
            'official_station' => $request->official_station,
            'availability_status' => $request->availability_status,
        ];
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

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