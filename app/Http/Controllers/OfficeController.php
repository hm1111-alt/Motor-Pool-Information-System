<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Office;
use App\Models\Employee;
use App\Models\Division;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class OfficeController extends Controller
{
    /**
     * Display a listing of the offices.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Office::query();
        
        // Handle search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('office_name', 'like', '%' . $search . '%')
                  ->orWhere('office_program', 'like', '%' . $search . '%')
                  ->orWhere('office_abbr', 'like', '%' . $search . '%')
                  ->orWhere('officer_code', 'like', '%' . $search . '%');
            });
        }
        
        // Handle status filter
        if ($request->has('status') && $request->status !== 'all') {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('office_isactive', $isActive);
        }
        
        $offices = $query->paginate(10)->appends($request->except('page'));
        
        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'table_body' => view('admin.offices.partials.table-body', compact('offices'))->render(),
                'pagination' => view('admin.offices.partials.pagination', compact('offices'))->render()
            ]);
        }
        
        return view('admin.offices.index', compact('offices'));
    }

    /**
     * Show the form for creating a new office.
     */
    public function create(): View
    {
        return view('admin.offices.create');
    }

    /**
     * Store a newly created office in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Log the request data for debugging
        \Log::info('Office store request data:', $request->all());
        
        $request->validate([
            'office_program' => 'required|string|max:255',
            'office_name' => 'required|string|max:255',
            'office_abbr' => 'required|string|max:50',
            'officer_code' => 'required|string|max:50',
            'office_isactive' => 'boolean',
        ]);

        // Handle checkbox value properly
        $isActive = $request->has('office_isactive') && $request->office_isactive == '1' ? 1 : 0;

        $office = Office::create([
            'office_program' => $request->office_program,
            'office_name' => $request->office_name,
            'office_abbr' => $request->office_abbr,
            'officer_code' => $request->officer_code,
            'office_isactive' => $isActive,
        ]);

        \Log::info('Office created successfully:', $office->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Office created successfully.',
                'data' => $office
            ]);
        }

        return redirect()->route('admin.offices.index')
                         ->with('success', 'Office created successfully.');
    }

    /**
     * Show the form for editing the specified office.
     */
    public function edit(Office $office): View
    {
        return view('admin.offices.edit', compact('office'));
    }

    /**
     * Update the specified office in storage.
     */
    public function update(Request $request, Office $office): RedirectResponse|JsonResponse
    {
        $request->validate([
            'office_program' => 'required|string|max:255',
            'office_name' => 'required|string|max:255',
            'office_abbr' => 'required|string|max:50',
            'officer_code' => 'required|string|max:50',
            'office_isactive' => 'boolean',
        ]);

        // Handle checkbox value properly
        $isActive = $request->has('office_isactive') && $request->office_isactive == '1' ? 1 : 0;

        $office->update([
            'office_program' => $request->office_program,
            'office_name' => $request->office_name,
            'office_abbr' => $request->office_abbr,
            'officer_code' => $request->officer_code,
            'office_isactive' => $isActive,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Office updated successfully.',
                'data' => $office
            ]);
        }

        return redirect()->route('admin.offices.index')
                         ->with('success', 'Office updated successfully.');
    }

    /**
     * Remove the specified office from storage.
     */
    public function destroy(Office $office): RedirectResponse|JsonResponse
    {
        try {
            // Check for dependent records with detailed counts
            $divisionCount = $office->divisions()->count();
            
            // Check employee positions in divisions of this office
            $employeesWithPositions = 0;
            if ($divisionCount > 0) {
                $divisionIds = $office->divisions->pluck('id')->toArray();
                $employeesWithPositions = \App\Models\EmpPosition::whereIn('division_id', $divisionIds)->count();
            }
            
            // If there are dependent records, provide detailed error message
            if ($divisionCount > 0 || $employeesWithPositions > 0) {
                $message = "Cannot delete office '{$office->office_name}' because it has dependent records:";
                
                if ($divisionCount > 0) {
                    $message .= "\n- {$divisionCount} division(s)";
                }
                
                if ($employeesWithPositions > 0) {
                    $message .= "\n- {$employeesWithPositions} employee position(s) in these divisions";
                }
                
                $message .= "\n\nPlease reassign or delete these dependent records before deleting the office.";
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'dependencies' => [
                            'divisions' => $divisionCount,
                            'employee_positions' => $employeesWithPositions
                        ]
                    ], 422);
                }
                
                return redirect()->route('admin.offices.index')
                                 ->with('error', $message);
            }
            
            // Safe to delete
            $officeName = $office->office_name;
            $office->delete();
            
            $successMessage = "Office '{$officeName}' deleted successfully.";
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }
            
            return redirect()->route('admin.offices.index')
                             ->with('success', $successMessage);
        } catch (\Exception $e) {
            \Log::error('Error deleting office: ' . $e->getMessage());
            \Log::error('Office ID: ' . $office->id . ', Name: ' . $office->office_name);
            
            $errorMessage = 'There was an error deleting the office: ' . $e->getMessage();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->route('admin.offices.index')
                             ->with('error', $errorMessage);
        }
    }
}