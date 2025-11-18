<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Division;
use App\Models\Unit;
use App\Models\Subunit;
use App\Models\ClassModel;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Employee::with(['office', 'division', 'unit', 'subunit', 'class']);
        
        // Handle search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('position_name', 'like', '%' . $search . '%');
            });
        }
        
        // Handle office filter
        if ($request->has('office') && $request->office !== 'all') {
            $query->where('office_id', $request->office);
        }
        
        // Handle class filter
        if ($request->has('class') && $request->class !== 'all') {
            $query->where('class_id', $request->class);
        }
        
        // Handle status filter
        if ($request->has('status') && $request->status !== 'all') {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('emp_status', $isActive);
        }
        
        $employees = $query->paginate(10)->appends($request->except('page'));
        $offices = Office::all();
        $classes = ClassModel::all();
        
        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'table_body' => view('admin.employees.partials.table-body', compact('employees'))->render(),
                'pagination' => view('admin.employees.partials.pagination', compact('employees'))->render()
            ]);
        }
        
        return view('admin.employees.index', compact('employees', 'offices', 'classes'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create(): View
    {
        $offices = Office::all();
        $divisions = collect(); // Will be loaded via AJAX
        $units = collect(); // Will be loaded via AJAX
        $subunits = collect(); // Will be loaded via AJAX
        $classes = ClassModel::all();
        
        return view('admin.employees.create', compact('offices', 'divisions', 'units', 'subunits', 'classes'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'ext_name' => 'nullable|string|max:10',
            'sex' => 'required|string|in:M,F',
            'prefix' => 'nullable|string|max:10',
            'position_name' => 'required|string|max:255',
            'office_id' => 'required|exists:offices,id',
            'division_id' => 'required|exists:divisions,id',
            'unit_id' => 'required|exists:units,id',
            'subunit_id' => 'required|exists:subunits,id',
            'class_id' => 'required|exists:class,id',
            'emp_status' => 'required|boolean',
            'is_divisionhead' => 'boolean',
            'is_vp' => 'boolean',
        ]);

        // Handle checkbox values properly
        $isActive = $request->has('emp_status') && $request->emp_status == '1' ? 1 : 0;
        $isDivisionHead = $request->has('is_divisionhead') && $request->is_divisionhead == '1' ? 1 : 0;
        $isVP = $request->has('is_vp') && $request->is_vp == '1' ? 1 : 0;

        $employee = Employee::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial,
            'ext_name' => $request->ext_name,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'full_name2' => $request->last_name . ', ' . $request->first_name,
            'sex' => $request->sex,
            'prefix' => $request->prefix,
            'position_name' => $request->position_name,
            'office_id' => $request->office_id,
            'division_id' => $request->division_id,
            'unit_id' => $request->unit_id,
            'subunit_id' => $request->subunit_id,
            'class_id' => $request->class_id,
            'emp_status' => $isActive,
            'is_divisionhead' => $isDivisionHead,
            'is_vp' => $isVP,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully.',
                'data' => $employee
            ]);
        }

        return redirect()->route('admin.employees.index')
                         ->with('success', 'Employee created successfully.');
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee): View
    {
        $offices = Office::all();
        $divisions = Division::where('office_id', $employee->office_id)->get();
        $units = Unit::where('division_id', $employee->division_id)->get();
        $subunits = Subunit::where('unit_id', $employee->unit_id)->get();
        $classes = ClassModel::all();
        
        return view('admin.employees.edit', compact('employee', 'offices', 'divisions', 'units', 'subunits', 'classes'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee): RedirectResponse|JsonResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'ext_name' => 'nullable|string|max:10',
            'sex' => 'required|string|in:M,F',
            'prefix' => 'nullable|string|max:10',
            'position_name' => 'required|string|max:255',
            'office_id' => 'required|exists:offices,id',
            'division_id' => 'required|exists:divisions,id',
            'unit_id' => 'required|exists:units,id',
            'subunit_id' => 'required|exists:subunits,id',
            'class_id' => 'required|exists:class,id',
            'emp_status' => 'required|boolean',
            'is_divisionhead' => 'boolean',
            'is_vp' => 'boolean',
        ]);

        // Handle checkbox values properly
        $isActive = $request->has('emp_status') && $request->emp_status == '1' ? 1 : 0;
        $isDivisionHead = $request->has('is_divisionhead') && $request->is_divisionhead == '1' ? 1 : 0;
        $isVP = $request->has('is_vp') && $request->is_vp == '1' ? 1 : 0;

        $employee->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial,
            'ext_name' => $request->ext_name,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'full_name2' => $request->last_name . ', ' . $request->first_name,
            'sex' => $request->sex,
            'prefix' => $request->prefix,
            'position_name' => $request->position_name,
            'office_id' => $request->office_id,
            'division_id' => $request->division_id,
            'unit_id' => $request->unit_id,
            'subunit_id' => $request->subunit_id,
            'class_id' => $request->class_id,
            'emp_status' => $isActive,
            'is_divisionhead' => $isDivisionHead,
            'is_vp' => $isVP,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully.',
                'data' => $employee
            ]);
        }

        return redirect()->route('admin.employees.index')
                         ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee): RedirectResponse|JsonResponse
    {
        try {
            $employee->delete();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Employee deleted successfully.'
                ]);
            }
            
            return redirect()->route('admin.employees.index')
                             ->with('success', 'Employee deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting employee: ' . $e->getMessage());
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'There was an error deleting the employee.'
                ], 500);
            }
            
            return redirect()->route('admin.employees.index')
                             ->with('error', 'There was an error deleting the employee.');
        }
    }

    /**
     * Get divisions by office ID
     */
    public function getDivisionsByOffice(Request $request)
    {
        $divisions = Division::where('office_id', $request->office_id)->get();
        return response()->json($divisions);
    }

    /**
     * Get units by division ID
     */
    public function getUnitsByDivision(Request $request)
    {
        $units = Unit::where('division_id', $request->division_id)->get();
        return response()->json($units);
    }

    /**
     * Get subunits by unit ID
     */
    public function getSubunitsByUnit(Request $request)
    {
        $subunits = Subunit::where('unit_id', $request->unit_id)->get();
        return response()->json($subunits);
    }
}