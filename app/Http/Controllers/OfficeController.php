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
    public function store(Request $request)
    {
        // Log the request data for debugging
        \Log::info('Office store request data:', $request->all());
        
        $request->validate([
            'program' => 'required|string|max:255',
            'office_name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:50',
            'code' => 'required|string|max:50',
        ]);

        // Status is always Active for new offices
        $isActive = 1;

        $office = Office::create([
            'office_program' => $request->program,
            'office_name' => $request->office_name,
            'office_abbr' => $request->abbreviation,
            'officer_code' => $request->code,
            'office_isactive' => $isActive,
        ]);

        \Log::info('Office created successfully:', $office->toArray());

        // For AJAX/modal requests, return JSON response
        if ($request->wantsJson() || $request->ajax()) {
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
    public function update(Request $request, Office $office)
    {
        $request->validate([
            'program' => 'required|string|max:255',
            'office_name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:50',
            'code' => 'required|string|max:50',
            'status' => 'required|in:Active,Inactive',
        ]);

        // Convert status to boolean for database storage
        $isActive = $request->status === 'Active' ? 1 : 0;

        $office->update([
            'office_program' => $request->program,
            'office_name' => $request->office_name,
            'office_abbr' => $request->abbreviation,
            'officer_code' => $request->code,
            'office_isactive' => $isActive,
        ]);

        // For AJAX/modal requests, return JSON response
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Office updated successfully.',
                'data' => $office
            ]);
        }

        return redirect()->route('admin.offices.index')
                         ->with('success', 'Office updated successfully.');
    }


}