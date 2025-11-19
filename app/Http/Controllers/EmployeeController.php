<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Division;
use App\Models\Unit;
use App\Models\Subunit;
use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Employee::with(['office', 'division', 'unit', 'subunit', 'class', 'user']); // Load user relationship
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('position_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply office filter
        if ($request->filled('office') && $request->office !== 'all') {
            $query->where('office_id', $request->office);
        }
        
        // Apply division filter
        if ($request->filled('division') && $request->division !== 'all') {
            $query->where('division_id', $request->division);
        }
        
        // Apply unit filter
        if ($request->filled('unit') && $request->unit !== 'all') {
            $query->where('unit_id', $request->unit);
        }
        
        // Apply subunit filter
        if ($request->filled('subunit') && $request->subunit !== 'all') {
            $query->where('subunit_id', $request->subunit);
        }
        
        // Apply class filter
        if ($request->filled('class') && $request->class !== 'all') {
            $query->where('class_id', $request->class);
        }
        
        // Apply status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status === 'active' ? 1 : 0;
            $query->where('emp_status', $status);
        }
        
        $employees = $query->paginate(10);
        $offices = Office::all();
        $classes = ClassModel::all();
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'table_body' => view('admin.employees.partials.table-body', compact('employees'))->render(),
                'pagination' => view('admin.employees.partials.pagination', ['employees' => $employees])->render()
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
        $classes = ClassModel::all();
        
        return view('admin.employees.create', compact('offices', 'classes'));
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'position_name' => 'required|string|max:255',
            'office_id' => 'nullable|exists:offices,id',
            'division_id' => 'nullable|exists:divisions,id',
            'unit_id' => 'nullable|exists:units,id',
            'subunit_id' => 'nullable|exists:subunits,id',
            'class_id' => 'nullable|exists:class,id',
            'emp_status' => 'required|boolean',
            'is_head' => 'boolean',
            'is_divisionhead' => 'boolean',
            'is_vp' => 'boolean',
            'is_president' => 'boolean',
        ]);

        // Handle checkbox values properly
        $isActive = $request->has('emp_status') && $request->emp_status == '1' ? 1 : 0;
        $isHead = $request->has('is_head') && $request->is_head == '1' ? 1 : 0;
        $isDivisionHead = $request->has('is_divisionhead') && $request->is_divisionhead == '1' ? 1 : 0;
        $isVP = $request->has('is_vp') && $request->is_vp == '1' ? 1 : 0;
        $isPresident = $request->has('is_president') && $request->is_president == '1' ? 1 : 0;

        // Create the user first
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => User::ROLE_EMPLOYEE,
        ]);

        // Create the employee and link to the user
        $employee = Employee::create([
            'user_id' => $user->id,
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
            'is_head' => $isHead,
            'is_divisionhead' => $isDivisionHead,
            'is_vp' => $isVP,
            'is_president' => $isPresident,
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
        $employee->load('user'); // Load the user relationship
        
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
            'email' => 'required|email|unique:users,email,' . ($employee->user_id ?? 0),
            'password' => 'nullable|string|min:8|confirmed',
            'position_name' => 'required|string|max:255',
            'office_id' => 'nullable|exists:offices,id',
            'division_id' => 'nullable|exists:divisions,id',
            'unit_id' => 'nullable|exists:units,id',
            'subunit_id' => 'nullable|exists:subunits,id',
            'class_id' => 'nullable|exists:class,id',
            'emp_status' => 'required|boolean',
            'is_head' => 'boolean',
            'is_divisionhead' => 'boolean',
            'is_vp' => 'boolean',
            'is_president' => 'boolean',
        ]);

        // Handle checkbox values properly
        $isActive = $request->has('emp_status') && $request->emp_status == '1' ? 1 : 0;
        $isHead = $request->has('is_head') && $request->is_head == '1' ? 1 : 0;
        $isDivisionHead = $request->has('is_divisionhead') && $request->is_divisionhead == '1' ? 1 : 0;
        $isVP = $request->has('is_vp') && $request->is_vp == '1' ? 1 : 0;
        $isPresident = $request->has('is_president') && $request->is_president == '1' ? 1 : 0;

        // Update the user if it exists
        if ($employee->user) {
            $userData = [
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
            ];
            
            // Only update password if provided
            if ($request->filled('password')) {
                $userData['password'] = bcrypt($request->password);
            }
            
            $employee->user->update($userData);
        } else {
            // Create user if it doesn't exist
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => User::ROLE_EMPLOYEE,
            ]);
            
            // Update employee with user_id
            $employee->user_id = $user->id;
        }

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
            'is_head' => $isHead,
            'is_divisionhead' => $isDivisionHead,
            'is_vp' => $isVP,
            'is_president' => $isPresident,
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