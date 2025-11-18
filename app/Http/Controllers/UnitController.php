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
            $query->where('division_id', $request->division);
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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'unit_name' => 'required|string|max:255',
            'unit_abbr' => 'required|string|max:100',
            'division_id' => 'required|exists:divisions,id',
            'unit_code' => 'required|string|max:50',
            'unit_isactive' => 'boolean',
        ]);

        // Handle checkbox value properly
        $isActive = $request->has('unit_isactive') && $request->unit_isactive == '1' ? 1 : 0;

        $unit = Unit::create([
            'unit_name' => $request->unit_name,
            'unit_abbr' => $request->unit_abbr,
            'division_id' => $request->division_id,
            'unit_code' => $request->unit_code,
            'unit_isactive' => $isActive,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit created successfully.',
                'data' => $unit
            ]);
        }

        return redirect()->route('admin.units.index')
                         ->with('success', 'Unit created successfully.');
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit): View
    {
        $divisions = Division::with('office')->get();
        return view('admin.units.edit', compact('unit', 'divisions'));
    }

    /**
     * Update the specified unit in storage.
     */
    public function update(Request $request, Unit $unit): RedirectResponse|JsonResponse
    {
        $request->validate([
            'unit_name' => 'required|string|max:255',
            'unit_abbr' => 'required|string|max:100',
            'division_id' => 'required|exists:divisions,id',
            'unit_code' => 'required|string|max:50',
            'unit_isactive' => 'boolean',
        ]);

        // Handle checkbox value properly
        $isActive = $request->has('unit_isactive') && $request->unit_isactive == '1' ? 1 : 0;

        $unit->update([
            'unit_name' => $request->unit_name,
            'unit_abbr' => $request->unit_abbr,
            'division_id' => $request->division_id,
            'unit_code' => $request->unit_code,
            'unit_isactive' => $isActive,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit updated successfully.',
                'data' => $unit
            ]);
        }

        return redirect()->route('admin.units.index')
                         ->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified unit from storage.
     */
    public function destroy(Unit $unit): RedirectResponse|JsonResponse
    {
        try {
            // Check if unit has related records
            if ($unit->employees()->count() > 0 || $unit->subunits()->count() > 0) {
                $message = 'Cannot delete unit because it has related records (employees or subunits). Please remove or reassign these records first.';
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->route('admin.units.index')
                                 ->with('error', $message);
            }
            
            $unit->delete();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unit deleted successfully.'
                ]);
            }
            
            return redirect()->route('admin.units.index')
                             ->with('success', 'Unit deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting unit: ' . $e->getMessage());
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'There was an error deleting the unit.'
                ], 500);
            }
            
            return redirect()->route('admin.units.index')
                             ->with('error', 'There was an error deleting the unit.');
        }
    }
}