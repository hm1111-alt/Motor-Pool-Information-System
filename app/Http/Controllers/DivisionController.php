<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Office;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class DivisionController extends Controller
{
    /**
     * Display a listing of the divisions.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Division::with('office');
        
        // Handle search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('division_name', 'like', '%' . $search . '%')
                  ->orWhere('division_abbr', 'like', '%' . $search . '%')
                  ->orWhere('division_code', 'like', '%' . $search . '%');
            });
        }
        
        // Handle office filter
        if ($request->has('office') && $request->office !== 'all') {
            $query->where('office_id', $request->office);
        }
        
        // Handle status filter
        if ($request->has('status') && $request->status !== 'all') {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('division_isactive', $isActive);
        }
        
        $divisions = $query->paginate(10)->appends($request->except('page'));
        $offices = Office::all();
        
        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'table_body' => view('admin.divisions.partials.table-body', compact('divisions'))->render(),
                'pagination' => view('admin.divisions.partials.pagination', compact('divisions'))->render()
            ]);
        }
        
        return view('admin.divisions.index', compact('divisions', 'offices'));
    }

    /**
     * Show the form for creating a new division.
     */
    public function create(): View
    {
        $offices = Office::all();
        return view('admin.divisions.create', compact('offices'));
    }

    /**
     * Store a newly created division in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'division_name' => 'required|string|max:255',
            'division_abbr' => 'required|string|max:100',
            'office_id' => 'required|exists:offices,id',
            'division_code' => 'required|string|max:50',
            'division_isactive' => 'sometimes|in:0,1',
        ]);

        // Handle checkbox value properly
        $isActive = $request->has('division_isactive') && ($request->division_isactive == '1' || $request->division_isactive == 1 || $request->division_isactive == true) ? 1 : 0;

        $division = Division::create([
            'division_name' => $request->division_name,
            'division_abbr' => $request->division_abbr,
            'office_id' => $request->office_id,
            'division_code' => $request->division_code,
            'division_isactive' => $isActive,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Division created successfully.',
                'data' => $division
            ]);
        }

        return redirect()->route('admin.divisions.index')
                         ->with('success', 'Division created successfully.');
    }

    /**
     * Show the form for editing the specified division.
     */
    public function edit(Division $division): View
    {
        $offices = Office::all();
        return view('admin.divisions.edit', compact('division', 'offices'));
    }

    /**
     * Update the specified division in storage.
     */
    public function update(Request $request, Division $division): RedirectResponse|JsonResponse
    {
        $request->validate([
            'division_name' => 'required|string|max:255',
            'division_abbr' => 'required|string|max:100',
            'office_id' => 'required|exists:offices,id',
            'division_code' => 'required|string|max:50',
            'division_isactive' => 'sometimes|in:0,1',
        ]);

        // Handle checkbox value properly
        $isActive = $request->has('division_isactive') && ($request->division_isactive == '1' || $request->division_isactive == 1 || $request->division_isactive == true) ? 1 : 0;

        $division->update([
            'division_name' => $request->division_name,
            'division_abbr' => $request->division_abbr,
            'office_id' => $request->office_id,
            'division_code' => $request->division_code,
            'division_isactive' => $isActive,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Division updated successfully.',
                'data' => $division
            ]);
        }

        return redirect()->route('admin.divisions.index')
                         ->with('success', 'Division updated successfully.');
    }

    /**
     * Remove the specified division from storage.
     */
    public function destroy(Division $division): RedirectResponse|JsonResponse
    {
        try {
            // Check for dependent records with detailed counts
            $unitCount = $division->units()->count();
            $employeePositionCount = \App\Models\EmpPosition::where('division_id', $division->id)->count();
            
            // If there are dependent records, provide detailed error message
            if ($unitCount > 0 || $employeePositionCount > 0) {
                $message = "Cannot delete division '{$division->division_name}' because it has dependent records:";
                
                if ($unitCount > 0) {
                    $message .= "\n- {$unitCount} unit(s)";
                }
                
                if ($employeePositionCount > 0) {
                    $message .= "\n- {$employeePositionCount} employee position(s)";
                }
                
                $message .= "\n\nPlease reassign or delete these dependent records before deleting the division.";
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'dependencies' => [
                            'units' => $unitCount,
                            'employee_positions' => $employeePositionCount
                        ]
                    ], 422);
                }
                
                return redirect()->route('admin.divisions.index')
                                 ->with('error', $message);
            }
            
            // Safe to delete
            $divisionName = $division->division_name;
            $division->delete();
            
            $successMessage = "Division '{$divisionName}' deleted successfully.";
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }
            
            return redirect()->route('admin.divisions.index')
                             ->with('success', $successMessage);
        } catch (\Exception $e) {
            \Log::error('Error deleting division: ' . $e->getMessage());
            \Log::error('Division ID: ' . $division->id . ', Name: ' . $division->division_name . ', Office ID: ' . $division->office_id);
            
            $errorMessage = 'There was an error deleting the division: ' . $e->getMessage();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->route('admin.divisions.index')
                             ->with('error', $errorMessage);
        }
    }
}