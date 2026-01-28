<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
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
        $search = $request->input('search');
        $query = Driver::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $drivers = $query->paginate(10);

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
            'middle_initial' => 'nullable|string|max:10',
            'last_name' => 'required|string|max:255',
            'ext_name' => 'nullable|string|max:50',
            'sex' => 'required|in:M,F',
            'prefix' => 'nullable|string|max:10',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'availability_status' => 'required|in:Available,Not Available,On Duty,Off Duty',
        ]);

        // Create user with driver role
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'driver',
        ]);

        // Create driver
        $driver = Driver::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_initial' => $request->middle_initial,
            'last_name' => $request->last_name,
            'ext_name' => $request->ext_name,
            'sex' => $request->sex,
            'prefix' => $request->prefix,
            'availability_status' => $request->availability_status,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'full_name2' => $request->prefix ? $request->prefix . ' ' . $request->first_name . ' ' . $request->last_name : $request->first_name . ' ' . $request->last_name,
        ]);

        return redirect()->route('drivers.index')
            ->with('success', 'Driver created successfully.');
    }

    /**
     * Display the specified driver.
     */
    public function show(Driver $driver): View
    {
        $driver->load('user');
        return view('drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified driver.
     */
    public function edit(Driver $driver): View
    {
        $driver->load('user');
        return view('drivers.edit', compact('driver'));
    }

    /**
     * Update the specified driver in storage.
     */
    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'last_name' => 'required|string|max:255',
            'ext_name' => 'nullable|string|max:50',
            'sex' => 'required|in:M,F',
            'prefix' => 'nullable|string|max:10',
            'email' => 'required|email|unique:users,email,' . $driver->user->id,
            'availability_status' => 'required|in:Available,Not Available,On Duty,Off Duty',
        ]);

        // Update user
        $driver->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
        ]);

        // Update driver
        $driver->update([
            'first_name' => $request->first_name,
            'middle_initial' => $request->middle_initial,
            'last_name' => $request->last_name,
            'ext_name' => $request->ext_name,
            'sex' => $request->sex,
            'prefix' => $request->prefix,
            'availability_status' => $request->availability_status,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'full_name2' => $request->prefix ? $request->prefix . ' ' . $request->first_name . ' ' . $request->last_name : $request->first_name . ' ' . $request->last_name,
        ]);

        return redirect()->route('drivers.index')
            ->with('success', 'Driver updated successfully.');
    }

    /**
     * Remove the specified driver from storage.
     */
    public function destroy(Driver $driver): RedirectResponse
    {
        // Delete user and driver
        $driver->user->delete();
        $driver->delete();

        return redirect()->route('drivers.index')
            ->with('success', 'Driver deleted successfully.');
    }
}