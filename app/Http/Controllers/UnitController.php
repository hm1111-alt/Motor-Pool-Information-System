<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Division;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class UnitController extends Controller
{
    /**
     * Display a listing of the units.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Unit::with('division.office');
        
        // Handle search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('unit_name', 'like', '%' . $search . '%')
                  ->orWhere('unit_abbr', 'like', '%' . $search . '%')
                  ->orWhere('unit_code', 'like', '%' . $search . '%');
            });
        }
        
        // Handle division filter
        if ($request->has('division') && $request->division !== 'all') {
            $query->where('unit_division', $request->division);
        }
        
        // Handle status filter
        if ($request->has('status') && $request->status !== 'all') {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('unit_isactive', $isActive);
        }
        
        $units = $query->paginate(10)->appends($request->except('page'));
        $divisions = Division::all();
        
        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'table_body' => view('admin.units.partials.table-body', compact('units'))->render(),
                'pagination' => view('admin.units.partials.pagination', compact('units'))->render()
            ]);
        }
        
        return view('admin.units.index', compact('units', 'divisions'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create(): View
    {
        $divisions = Division::with('office')->get();
        return view('admin.units.create', compact('divisions'));
    }
    /**
 * Store a newly created unit in storage.
 */
public function store(Request $request): RedirectResponse|JsonResponse
{
    try {
        // Log the incoming request for debugging
        \Log::info('Unit store request received');
        \Log::info('Request data: ' . json_encode($request->all()));
        \Log::info('Request is AJAX: ' . ($request->ajax() ? 'true' : 'false'));

        // Validate the incoming data
        $request->validate([
            'unit_name' => 'required|string|max:255',
            'unit_abbr' => 'required|string|max:100',
            'division_id' => 'required|exists:lib_divisions,id_division', // correct table
            'unit_code' => 'required|string|max:5',
            'unit_isactive' => 'boolean',
        ]);

        // Get the division to fetch the related office
        $division = Division::with('office')->findOrFail($request->division_id);

        // Determine active status
        $isActive = $request->has('unit_isactive') && $request->unit_isactive == '1' ? 1 : 0;

        // Create the unit
        $unit = Unit::create([
            'unit_name' => $request->unit_name,
            'unit_abbr' => $request->unit_abbr,
            'unit_division' => $request->division_id,
            'unit_office' => $division->office_id,
            'unit_code' => $request->unit_code,
            'unit_isactive' => $isActive,
        ]);

        \Log::info('Unit created successfully: ' . $unit->id . ' - ' . $unit->unit_name);

        // Respond differently for AJAX (modal) vs normal form
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit created successfully.',
                'data' => $unit
            ]);
        }

        return redirect()->route('admin.units.index')
                         ->with('success', 'Unit created successfully.');

    } catch (\Exception $e) {
        \Log::error('Error creating unit: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());

        // Respond for AJAX (modal) vs normal form
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the unit: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->route('admin.units.index')
                         ->with('error', 'An error occurred while creating the unit.');
    }
}
public function update(Request $request, $id)
{
    try {
        // Validate the incoming data
        $request->validate([
            'unit_name' => 'required|string|max:255',
            'unit_abbr' => 'required|string|max:100',
            'division_id' => 'required|exists:lib_divisions,id_division',
            'unit_code' => 'required|string|max:5',
            'unit_isactive' => 'boolean',
        ]);

        $unit = Unit::findOrFail($id);

        $unit->update([
            'unit_name' => $request->unit_name,
            'unit_abbr' => $request->unit_abbr,
            'unit_code' => $request->unit_code,
            'unit_division' => $request->division_id,
            'unit_isactive' => $request->unit_isactive
        ]);

        // Respond differently for AJAX (modal) vs normal form
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit updated successfully.',
                'data' => $unit
            ]);
        }

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit updated successfully.');

    } catch (\Exception $e) {
        \Log::error('Error updating unit: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());

        // Respond for AJAX (modal) vs normal form
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the unit: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->route('admin.units.index')
            ->with('error', 'An error occurred while updating the unit.');
    }
}
}