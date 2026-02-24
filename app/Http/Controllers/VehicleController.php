<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class VehicleController extends Controller
{
    /**
     * Display a listing of the vehicles.
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->get('search');
        $type = $request->get('type');
        
        $query = Vehicle::where('status', 'Active')
            ->when($search, function ($query, $search) {
                return $query->where('plate_number', 'LIKE', "%{$search}%")
                            ->orWhere('model', 'LIKE', "%{$search}%")
                            ->orWhere('type', 'LIKE', "%{$search}%");
            })
            ->when($type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->orderBy('created_at', 'desc');
        
        $vehicles = $query->paginate(10);
        
        // Get all distinct types for the filter dropdown
        $types = Vehicle::select('type')->distinct()->pluck('type')->filter();
        
        // Check if this is an AJAX request for partial updates
        if ($request->ajax() || $request->get('ajax')) {
            return response()->json([
                'table_body' => view('vehicles.partials.table-rows', compact('vehicles', 'search', 'type', 'types'))->render(),
                'pagination_info' => [
                    'current_page' => $vehicles->currentPage(),
                    'last_page' => $vehicles->lastPage(),
                    'first_item' => $vehicles->firstItem() ?? 0,
                    'last_item' => $vehicles->lastItem() ?? 0,
                    'total' => $vehicles->total(),
                ]
            ]);
        }

        // Get only Active vehicles for PDF export
        $activeVehicles = Vehicle::where('status', 'Active')->orderBy('created_at', 'desc')->get();
        
        return view('vehicles.index', compact('vehicles', 'search', 'type', 'types', 'activeVehicles'));
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
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'plate_number' => 'required|string|max:50|unique:vehicles,plate_number',
            'model' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'seating_capacity' => 'required|integer|min:1',
            'mileage' => 'required|integer|min:0',
            'status' => 'required|in:Available,Not Available,Active,Under Maintenance,Inactive',
        ], [
            'plate_number.unique' => 'This has already been taken.',
        ]);

        $data = $request->except('picture');
        
        if ($request->hasFile('picture')) {
            // Store the image in the public directory
            $publicPath = public_path('vehicles/images/');
            $imageName = time() . '_' . $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move($publicPath, $imageName);
            $data['picture'] = $imageName;
        } else {
            // Use default image when no picture is uploaded
            $data['picture'] = 'vehicle_default.png';
        }

        $vehicle = Vehicle::create($data);

        // Handle AJAX request for modal submission
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Vehicle created successfully!',
                'vehicle' => $vehicle
            ]);
        }

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified vehicle.
     */
    public function show(Vehicle $vehicle): View
    {
        // Load vehicle with travel history
        $vehicle->load(['travelHistory.driver', 'travelHistory.tripTicket']);
        
        // Get travel history ordered by departure date
        $travelHistory = $vehicle->travelHistory()->with(['driver', 'tripTicket'])
            ->orderBy('departure_date', 'desc')
            ->orderBy('departure_time', 'desc')
            ->paginate(10);
        
        return view('vehicles.show', compact('vehicle', 'travelHistory'));
    }

    /**
     * Get vehicle data for editing via AJAX.
     */
    public function getForEdit(Vehicle $vehicle): JsonResponse
    {
        return response()->json([
            'success' => true,
            'vehicle' => [
                'id' => $vehicle->id,
                'model' => $vehicle->model,
                'type' => $vehicle->type,
                'plate_number' => $vehicle->plate_number,
                'fuel_type' => $vehicle->fuel_type,
                'seating_capacity' => $vehicle->seating_capacity,
                'mileage' => $vehicle->mileage,
                'status' => $vehicle->status,
                'picture' => $vehicle->picture
            ]
        ]);
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, Vehicle $vehicle): RedirectResponse|JsonResponse
    {
        // Debugging: Log the vehicle ID and plate number
        \Log::info('Updating vehicle ID: ' . $vehicle->id . ', Plate Number: ' . $vehicle->plate_number);
        \Log::info('Request data: ', $request->all());
        
        // Get the current plate number from the request
        $newPlateNumber = $request->input('plate_number');
        \Log::info('New plate number: ' . $newPlateNumber);
        \Log::info('Current vehicle plate number: ' . $vehicle->plate_number);
        
        try {
            $request->validate([
                'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'plate_number' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('vehicles', 'plate_number')->ignore($vehicle->id)
                ],
                'model' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'fuel_type' => 'required|string|max:255',
                'seating_capacity' => 'required|integer|min:1',
                'mileage' => 'required|integer|min:0',
                'status' => 'sometimes|in:Available,Not Available,Active,Under Maintenance',
            ], [
                'plate_number.unique' => 'This has already been taken.',
            ]);
        } catch (ValidationException $e) {
            // Handle validation errors for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                $errors = $e->errors();
                
                // Check if it's a plate number duplicate error
                if (isset($errors['plate_number'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This has already been taken.',
                        'errors' => $errors
                    ], 422);
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $errors
                ], 422);
            }
            
            // Re-throw for non-AJAX requests
            throw $e;
        }

        $data = $request->except('picture');
        
        // If picture is not provided, don't include it in the update data
        if (!$request->hasFile('picture')) {
            unset($data['picture']);
        }
        
        if ($request->hasFile('picture')) {
            // Delete old picture if exists (but not the default image)
            $publicPath = public_path('vehicles/images/');
            if ($vehicle->picture && $vehicle->picture !== 'vehicle_default.png') {
                $oldImagePath = $publicPath . basename($vehicle->picture);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Store the new image in the public directory
            $imageName = time() . '_' . $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move($publicPath, $imageName);
            $data['picture'] = $imageName;
        } else {
            // If no new picture uploaded, keep existing picture or set default
            if (!$vehicle->picture) {
                $data['picture'] = 'vehicle_default.png';
            }
        }

        $vehicle->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Vehicle updated successfully!',
                'vehicle' => $vehicle
            ]);
        }

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy(Vehicle $vehicle): RedirectResponse|JsonResponse
    {
        try {
            // Archive the vehicle instead of deleting it
            $vehicle->update(['status' => 'Inactive']);
            
            // Check if this is an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vehicle archived successfully!'
                ]);
            }
            
            return redirect()->route('vehicles.index')
                ->with('success', 'Vehicle archived successfully!');
                
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error archiving vehicle: ' . $e->getMessage());
            
            // Check if this is an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to archive vehicle: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('vehicles.index')
                ->with('error', 'Failed to archive vehicle: ' . $e->getMessage());
        }
    }
    
    /**
     * Check if plate number already exists
     */
    public function checkPlateNumber(Request $request, string $plateNumber): JsonResponse
    {
        $query = Vehicle::where('plate_number', $plateNumber);
        
        // Exclude specific vehicle ID if provided (for edit form)
        if ($request->has('exclude_id')) {
            $query->where('id', '!=', $request->exclude_id);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'exists' => $exists
        ]);
    }
}