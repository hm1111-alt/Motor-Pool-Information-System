<?php

namespace App\Http\Controllers;

use App\Models\VehicleMaintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class VehicleMaintenanceController extends Controller
{
    /**
     * Display a listing of the maintenance records.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $vehicleId = $request->get('vehicle_id');
        
        // Get all vehicles for the filter dropdown
        $vehicles = Vehicle::orderBy('plate_number')->get();
        
        // Build the query
        $query = VehicleMaintenance::with('vehicle')->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('make_or_type', 'LIKE', "%{$search}%")
                         ->orWhere('person_office_unit', 'LIKE', "%{$search}%")
                         ->orWhere('place', 'LIKE', "%{$search}%")
                         ->orWhere('nature_of_work', 'LIKE', "%{$search}%")
                         ->orWhere('mechanic_assigned', 'LIKE', "%{$search}%")
                         ->orWhere('conforme', 'LIKE', "%{$search}%");
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($vehicleId) {
            $query->where('vehicle_id', $vehicleId);
        }
        
        $maintenanceRecords = $query->paginate(10)->appends($request->except('page'));
        
        return view('vehicle-maintenance.index', compact('maintenanceRecords', 'vehicles', 'vehicleId', 'status', 'search'));
    }

    /**
     * Show the form for creating a new maintenance record.
     */
    public function create(): View
    {
        $vehicles = Vehicle::orderBy('plate_number')->get();
        return view('vehicle-maintenance.create', compact('vehicles'));
    }

    /**
     * Store a newly created maintenance record in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'odometer_reading' => 'nullable|integer|min:0',
            'date_started' => 'required|date',
            'make_or_type' => 'required|string|max:255',
            'person_office_unit' => 'required|string|max:255',
            'place' => 'required|string|max:255',
            'nature_of_work' => 'required|string',
            'materials_parts' => 'nullable|string',
            'mechanic_assigned' => 'required|string|max:255',
            'conforme' => 'required|string|max:255',
            'status' => 'required|in:Pending,Ongoing,Completed',
        ]);

        VehicleMaintenance::create($request->all());

        return redirect()->route('vehicle-maintenance.index')
            ->with('success', 'Vehicle maintenance record created successfully.');
    }

    /**
     * Display the specified maintenance record.
     */
    public function show(VehicleMaintenance $vehicleMaintenance): View
    {
        $vehicleMaintenance->load('vehicle');
        return view('vehicle-maintenance.show', compact('vehicleMaintenance'));
    }

    /**
     * Show the form for editing the specified maintenance record.
     */
    public function edit(VehicleMaintenance $vehicleMaintenance): View
    {
        $vehicleMaintenance->load('vehicle');
        $vehicles = Vehicle::orderBy('plate_number')->get();
        return view('vehicle-maintenance.edit', compact('vehicleMaintenance', 'vehicles'));
    }

    /**
     * Update the specified maintenance record in storage.
     */
    public function update(Request $request, VehicleMaintenance $vehicleMaintenance): RedirectResponse
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'odometer_reading' => 'nullable|integer|min:0',
            'date_started' => 'required|date',
            'make_or_type' => 'required|string|max:255',
            'person_office_unit' => 'required|string|max:255',
            'place' => 'required|string|max:255',
            'nature_of_work' => 'required|string',
            'materials_parts' => 'nullable|string',
            'mechanic_assigned' => 'required|string|max:255',
            'date_completed' => 'nullable|date',
            'conforme' => 'required|string|max:255',
            'status' => 'required|in:Pending,Ongoing,Completed',
        ]);

        $vehicleMaintenance->update($request->all());

        return redirect()->route('vehicle-maintenance.index')
            ->with('success', 'Vehicle maintenance record updated successfully.');
    }

    /**
     * Remove the specified maintenance record from storage.
     */
    public function destroy(VehicleMaintenance $vehicleMaintenance): RedirectResponse
    {
        $vehicleMaintenance->delete();

        return redirect()->route('vehicle-maintenance.index')
            ->with('success', 'Vehicle maintenance record deleted successfully.');
    }
    
    /**
     * Display maintenance records for a specific vehicle.
     */
    public function maintenanceByVehicle(Vehicle $vehicle): View
    {
        $maintenanceRecords = VehicleMaintenance::where('vehicle_id', $vehicle->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('vehicle-maintenance.for-vehicle', compact('vehicle', 'maintenanceRecords'));
    }
}