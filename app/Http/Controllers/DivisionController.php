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
            'division_isactive' => 'boolean',
        ]);

        // Handle checkbox value properly
        $isActive = $request->has('division_isactive') && $request->division_isactive == '1' ? 1 : 0;

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
            'division_isactive' => 'boolean',
        ]);

        // Handle checkbox value properly
        $isActive = $request->has('division_isactive') && $request->division_isactive == '1' ? 1 : 0;

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
            // Check if division has related records
            if ($division->employees()->count() > 0 || $division->units()->count() > 0) {
                $message = 'Cannot delete division because it has related records (employees or units). Please remove or reassign these records first.';
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->route('admin.divisions.index')
                                 ->with('error', $message);
            }
            
            $division->delete();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Division deleted successfully.'
                ]);
            }
            
            return redirect()->route('admin.divisions.index')
                             ->with('success', 'Division deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting division: ' . $e->getMessage());
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'There was an error deleting the division.'
                ], 500);
            }
            
            return redirect()->route('admin.divisions.index')
                             ->with('error', 'There was an error deleting the division.');
        }
    }
}