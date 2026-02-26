<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subunit;
use App\Models\Unit;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SubunitController extends Controller
{
    /**
     * Display a listing of the subunits.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Subunit::whereExists(function($q) {
            $q->select(DB::raw(1))
              ->from('lib_units')
              ->whereColumn('lib_units.id', 'lib_subunits.unit_id');
        })->with('unit');
        
        // Handle search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subunit_name', 'like', '%' . $search . '%')
                  ->orWhere('subunit_abbr', 'like', '%' . $search . '%');
            });
        }
        
        // Handle unit filter
        if ($request->has('unit') && $request->unit !== 'all') {
            $query->where('unit_id', $request->unit);
        }
        
        // Handle status filter
        if ($request->has('status') && $request->status !== 'all') {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('subunit_isactive', $isActive);
        }
        
        $subunits = $query->paginate(10)->appends($request->except('page'));
        $units = Unit::all();
        
        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'table_body' => view('admin.subunits.partials.table-body', compact('subunits'))->render(),
                'pagination' => view('admin.subunits.partials.pagination', compact('subunits'))->render()
            ]);
        }
        
        return view('admin.subunits.index', compact('subunits', 'units'));
    }

    /**
     * Show the form for creating a new subunit.
     */
    public function create(): View
    {
        $units = Unit::with('division.office')->get();
        return view('admin.subunits.create', compact('units'));
    }

    /**
     * Store a newly created subunit in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $request->validate([
                'subunit_name' => 'required|string|max:255',
                'subunit_abbr' => 'required|string|max:100',
                'unit_id' => 'required|exists:lib_units,id',
            ]);
    
            // Automatically set status to active (1) for new subunits
            $isActive = 1;
    
            // Get the unit to populate office and division fields
            $unit = Unit::with('division')->find($request->unit_id);
                    
            $subunit = Subunit::create([
                'subunit_name' => $request->subunit_name,
                'subunit_abbr' => $request->subunit_abbr,
                'unit_id' => $request->unit_id,
                'subunit_office' => $unit && $unit->division ? $unit->division->office_id : null,
                'subunit_division' => $unit && $unit->division ? $unit->division->id_division : null,
                'subunit_isactive' => $isActive,
                'subunit_updated_date' => now(), // Manually set the custom timestamp
            ]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subunit created successfully.',
                    'data' => $subunit
                ]);
            }
    
            return redirect()->route('admin.subunits.index')
                             ->with('success', 'Subunit created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating subunit: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the subunit: ' . $e->getMessage()
                ], 500);
            }
    
            return redirect()->route('admin.subunits.index')
                             ->with('error', 'An error occurred while creating the subunit: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified subunit.
     */
    public function edit(Subunit $subunit): View
    {
        $units = Unit::with('division.office')->get();
        return view('admin.subunits.edit', compact('subunit', 'units'));
    }

    /**
     * Update the specified subunit in storage.
     */
    public function update(Request $request, Subunit $subunit): RedirectResponse|JsonResponse
    {
        try {
            $request->validate([
                'subunit_name' => 'required|string|max:255',
                'subunit_abbr' => 'required|string|max:100',
                'unit_id' => 'required|exists:lib_units,id',
                'subunit_isactive' => 'boolean',
            ]);
    
            // Handle checkbox value properly
            $isActive = $request->has('subunit_isactive') && $request->subunit_isactive == '1' ? 1 : 0;
    
            // Get the unit to populate office and division fields
            $unit = Unit::with('division')->find($request->unit_id);
                    
            $subunit->update([
                'subunit_name' => $request->subunit_name,
                'subunit_abbr' => $request->subunit_abbr,
                'unit_id' => $request->unit_id,
                'subunit_office' => $unit && $unit->division ? $unit->division->office_id : null,
                'subunit_division' => $unit && $unit->division ? $unit->division->id_division : null,
                'subunit_isactive' => $isActive,
                'subunit_updated_date' => now(), // Manually set the custom timestamp
            ]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subunit updated successfully.',
                    'data' => $subunit
                ]);
            }
    
            return redirect()->route('admin.subunits.index')
                             ->with('success', 'Subunit updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating subunit: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the subunit: ' . $e->getMessage()
                ], 500);
            }
    
            return redirect()->route('admin.subunits.index')
                             ->with('error', 'An error occurred while updating the subunit: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified subunit from storage.
     */
    public function destroy(Subunit $subunit): RedirectResponse|JsonResponse
    {
        try {
            // Check for dependent records with detailed counts
            $employeePositionCount = \App\Models\EmpPosition::where('subunit_id', $subunit->id)->count();
            
            // If there are dependent records, provide detailed error message
            if ($employeePositionCount > 0) {
                $message = "Cannot delete subunit '{$subunit->subunit_name}' because it has dependent records:";
                
                if ($employeePositionCount > 0) {
                    $message .= "\n- {$employeePositionCount} employee position(s)";
                }
                
                $message .= "\n\nPlease reassign or delete these dependent records before deleting the subunit.";
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'dependencies' => [
                            'employee_positions' => $employeePositionCount
                        ]
                    ], 422);
                }
                
                return redirect()->route('admin.subunits.index')
                                 ->with('error', $message);
            }
            
            // Safe to delete
            $subunitName = $subunit->subunit_name;
            $subunit->delete();
            
            $successMessage = "Subunit '{$subunitName}' deleted successfully.";
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }
            
            return redirect()->route('admin.subunits.index')
                             ->with('success', $successMessage);
        } catch (\Exception $e) {
            \Log::error('Error deleting subunit: ' . $e->getMessage());
            \Log::error('Subunit ID: ' . $subunit->id . ', Name: ' . $subunit->subunit_name . ', Unit ID: ' . $subunit->unit_id);
            
            $errorMessage = 'There was an error deleting the subunit: ' . $e->getMessage();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->route('admin.subunits.index')
                             ->with('error', $errorMessage);
        }
    }
}