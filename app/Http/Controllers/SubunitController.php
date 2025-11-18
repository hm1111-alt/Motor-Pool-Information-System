<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subunit;
use App\Models\Unit;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class SubunitController extends Controller
{
    /**
     * Display a listing of the subunits.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Subunit::with('unit.division.office');
        
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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'subunit_name' => 'required|string|max:255',
            'subunit_abbr' => 'required|string|max:100',
            'unit_id' => 'required|exists:units,id',
            'subunit_isactive' => 'boolean',
        ]);

        // Handle checkbox value properly
        $isActive = $request->has('subunit_isactive') && $request->subunit_isactive == '1' ? 1 : 0;

        $subunit = Subunit::create([
            'subunit_name' => $request->subunit_name,
            'subunit_abbr' => $request->subunit_abbr,
            'unit_id' => $request->unit_id,
            'subunit_isactive' => $isActive,
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
        $request->validate([
            'subunit_name' => 'required|string|max:255',
            'subunit_abbr' => 'required|string|max:100',
            'unit_id' => 'required|exists:units,id',
            'subunit_isactive' => 'boolean',
        ]);

        // Handle checkbox value properly
        $isActive = $request->has('subunit_isactive') && $request->subunit_isactive == '1' ? 1 : 0;

        $subunit->update([
            'subunit_name' => $request->subunit_name,
            'subunit_abbr' => $request->subunit_abbr,
            'unit_id' => $request->unit_id,
            'subunit_isactive' => $isActive,
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
    }

    /**
     * Remove the specified subunit from storage.
     */
    public function destroy(Subunit $subunit): RedirectResponse|JsonResponse
    {
        try {
            // Check if subunit has related records
            if ($subunit->employees()->count() > 0) {
                $message = 'Cannot delete subunit because it has related records (employees). Please remove or reassign these records first.';
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->route('admin.subunits.index')
                                 ->with('error', $message);
            }
            
            $subunit->delete();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subunit deleted successfully.'
                ]);
            }
            
            return redirect()->route('admin.subunits.index')
                             ->with('success', 'Subunit deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting subunit: ' . $e->getMessage());
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'There was an error deleting the subunit.'
                ], 500);
            }
            
            return redirect()->route('admin.subunits.index')
                             ->with('error', 'There was an error deleting the subunit.');
        }
    }
}