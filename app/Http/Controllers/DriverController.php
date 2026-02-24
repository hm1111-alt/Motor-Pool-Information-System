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
        $position = $request->get('position');
            
        $query = Driver::with('user');
            
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('email', 'LIKE', "%{$search}%");
                  });
            });
        }
            
        // Apply position filter
        if ($position) {
            $query->where('position', $position);
        }
            
        $query->orderBy('created_at', 'desc');
            
        $drivers = $query->paginate(10)->appends($request->except('page'));
        $positions = Driver::select('position')->distinct()->orderBy('position')->pluck('position');
        $users = User::orderBy('name')->get();
        
        // Get all drivers for PDF generation (without pagination)
        $allDriversQuery = Driver::with('user');
        
        // Apply same filters for PDF data
        if ($search) {
            $allDriversQuery->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('email', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        if ($position) {
            $allDriversQuery->where('position', $position);
        }
        
        $allDriversQuery->orderBy('created_at', 'desc');
        $allDrivers = $allDriversQuery->get();
            
        return view('drivers.index', compact('drivers', 'search', 'positions', 'position', 'users', 'allDrivers'));
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
    public function store(Request $request)
    {
        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson() || $request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
            try {
                $request->validate([
                    'first_name' => 'required|string|max:255',
                    'middle_initial' => 'nullable|string|max:10',
                    'last_name' => 'required|string|max:255',
                    'contact_num' => 'required|string|size:11|unique:users,contact_num',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|string|min:8|confirmed',
                    'address' => 'required|string',
                    'position' => 'required|string|max:255',
                    'official_station' => 'required|string|max:255',
                ]);

                // Generate full names
                $fullName = $request->first_name . ' ' . $request->last_name;
                $fullName2 = $request->first_name;
                if ($request->middle_initial) {
                    $fullName2 .= ' ' . $request->middle_initial . '.';
                }
                $fullName2 .= ' ' . $request->last_name;

                // Create user account
                $user = User::create([
                    'name' => $fullName,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'contact_num' => $request->contact_num,
                    'role' => 'driver'
                ]);

                // Create driver record
                Driver::create([
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'middle_initial' => $request->middle_initial,
                    'last_name' => $request->last_name,
                    'full_name' => $fullName,
                    'full_name2' => $fullName2,
                    'address' => $request->address,
                    'position' => $request->position,
                    'official_station' => $request->official_station,
                    'availability_status' => 'Available',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Driver created successfully.'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
        }

        // Regular form submission (fallback)
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'required|string|size:11|unique:users,contact_num',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string',
            'position' => 'required|string|max:255',
            'official_station' => 'required|string|max:255',
        ]);

        // Generate full names
        $fullName = $request->first_name . ' ' . $request->last_name;
        $fullName2 = $request->first_name;
        if ($request->middle_initial) {
            $fullName2 .= ' ' . $request->middle_initial . '.';
        }
        $fullName2 .= ' ' . $request->last_name;

        // Create user account
        $user = User::create([
            'name' => $fullName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact_num' => $request->contact_num,
            'role' => 'driver'
        ]);

        // Create driver record
        Driver::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_initial' => $request->middle_initial,
            'last_name' => $request->last_name,
            'full_name' => $fullName,
            'full_name2' => $fullName2,
            'address' => $request->address,
            'position' => $request->position,
            'official_station' => $request->official_station,
            'availability_status' => 'Available',
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
        
        // Get the current assigned vehicle (most recent approved itinerary)
        $currentItinerary = $driver->itineraries()
            ->where('status', 'Approved')
            ->orderBy('created_at', 'desc')
            ->first();
            
        $currentVehicle = $currentItinerary ? $currentItinerary->vehicle : null;
        
        return view('drivers.show', compact('driver', 'currentVehicle'));
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
    public function update(Request $request, Driver $driver)
    {
        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson() || $request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
            try {
                $request->validate([
                    'first_name' => 'required|string|max:255',
                    'middle_initial' => 'nullable|string|max:10',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:drivers,email,' . $driver->id,
                    'address' => 'required|string|max:500',
                    'contact_num' => 'required|string|size:11|unique:drivers,contact_num,' . $driver->id,
                    'position' => 'required|string|max:255',
                    'official_station' => 'required|string|max:255',
                    'password' => 'nullable|string|min:8|confirmed',
                ]);

                // Generate full names
                $fullName = $request->first_name . ' ' . $request->last_name;
                $fullName2 = $request->first_name;
                if ($request->middle_initial) {
                    $fullName2 .= ' ' . $request->middle_initial . '.';
                }
                $fullName2 .= ' ' . $request->last_name;

                // Update driver information
                $driver->update([
                    'firsts_name' => $request->first_name,
                    'middle_initial' => $request->middle_initial,
                    'last_name' => $request->last_name,
                    'full_name' => $fullName,
                    'full_name2' => $fullName2,
                    'email' => $request->email,
                    'address' => $request->address,
                    'contact_num' => $request->contact_num,
                    'position' => $request->position,
                    'official_station' => $request->official_station,
                ]);

                // Update user password if provided
                if ($request->filled('password')) {
                    $driver->user->update([
                        'password' => Hash::make($request->password)
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Driver updated successfully'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
        }

        // Non-AJAX request - traditional form submission
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:drivers,email,' . $driver->id,
            'address' => 'required|string|max:500',
            'contact_num' => 'required|string|size:11|unique:drivers,contact_num,' . $driver->id,
            'position' => 'required|string|max:255',
            'official_station' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Generate full names
        $fullName = $request->first_name . ' ' . $request->last_name;
        $fullName2 = $request->first_name;
        if ($request->middle_initial) {
            $fullName2 .= ' ' . $request->middle_initial . '.';
        }
        $fullName2 .= ' ' . $request->last_name;

        // Update driver information
        $driver->update([
            'firsts_name' => $request->first_name,
            'middle_initial' => $request->middle_initial,
            'last_name' => $request->last_name,
            'full_name' => $fullName,
            'full_name2' => $fullName2,
            'email' => $request->email,
            'address' => $request->address,
            'contact_num' => $request->contact_num,
            'position' => $request->position,
            'official_station' => $request->official_station,
        ]);

        // Update user password if provided
        if ($request->filled('password')) {
            $driver->user->update([
                'password' => Hash::make($request->password)
            ]);
        }

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

    /**
     * Check if contact number already exists
     */
    public function checkContactNumber($contactNumber)
    {
        $exists = User::where('contact_num', $contactNumber)->exists();
        return response()->json(['exists' => $exists]);
    }
    
    /**
     * Check if email already exists
     */
    public function checkEmail($email)
    {
        $exists = User::where('email', $email)->exists();
        return response()->json(['exists' => $exists]);
    }

    /**
     * Generate PDF list of drivers
     */
    public function generatePDF(Request $request)
    {
        $position = $request->get('position');
        
        $drivers = Driver::with('user')
            ->when($position, function ($query, $position) {
                return $query->where('position', $position);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Create PDF content
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { margin: 0; font-size: 18px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #000; padding: 6px; text-align: left; }
                th { background-color: #f0f0f0; font-weight: bold; }
                .footer { margin-top: 30px; font-size: 10px; text-align: center; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LIST OF DRIVERS</h1>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Driver Name</th>
                        <th>Position</th>
                        <th>Official Station</th>
                        <th>Contact Number</th>
                    </tr>
                </thead>
                <tbody>';

        foreach($drivers as $index => $driver) {
            $html .= '
                <tr>
                    <td>' . ($index + 1) . '</td>
                    <td>' . htmlspecialchars($driver->full_name) . '</td>
                    <td>' . htmlspecialchars($driver->position) . '</td>
                    <td>' . htmlspecialchars($driver->official_station) . '</td>
                    <td>' . htmlspecialchars($driver->contact_num) . '</td>
                </tr>';
        }

        $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                Page 1 of 1<br>
                Generated on: ' . date('n/j/Y g:i:s A') . '
            </div>
        </body>
        </html>';

        // Return PDF response
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="drivers_list.html"');
    }
}