<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Officer;
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
        $query = Employee::with(['position', 'position.office', 'position.division', 'position.unit', 'position.subunit', 'position.class', 'user']); // Load user and position relationships
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhereHas('position', function ($positionQuery) use ($search) {
                      $positionQuery->where('position_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply office filter
        if ($request->filled('office') && $request->office !== 'all') {
            $query->whereHas('position', function ($q) use ($request) {
                $q->where('office_id', $request->office);
            });
        }
        
        // Apply division filter
        if ($request->filled('division') && $request->division !== 'all') {
            $query->whereHas('position', function ($q) use ($request) {
                $q->where('division_id', $request->division);
            });
        }
        
        // Apply unit filter
        if ($request->filled('unit') && $request->unit !== 'all') {
            $query->whereHas('position', function ($q) use ($request) {
                $q->where('unit_id', $request->unit);
            });
        }
        
        // Apply subunit filter
        if ($request->filled('subunit') && $request->subunit !== 'all') {
            $query->whereHas('position', function ($q) use ($request) {
                $q->where('subunit_id', $request->subunit);
            });
        }
        
        // Apply class filter
        if ($request->filled('class') && $request->class !== 'all') {
            $query->whereHas('position', function ($q) use ($request) {
                $q->where('class_id', $request->class);
            });
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
        $divisions = Division::all();
        $units = Unit::all();
        $subunits = Subunit::all();
        // Define available roles - these are for officer positions
        $roles = collect([
            (object)['id' => 0, 'name' => 'none'],
            (object)['id' => 1, 'name' => 'unit_head'],
            (object)['id' => 2, 'name' => 'division_head'],
            (object)['id' => 3, 'name' => 'vp'],
            (object)['id' => 4, 'name' => 'president'],
        ]);
        
        return view('admin.employees.create', compact('offices', 'classes', 'divisions', 'units', 'subunits', 'roles'));
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
            'position_role' => 'nullable|in:none,unit_head,division_head,vp,president',
            'additional_positions' => 'nullable|array',
            'additional_positions.*.position_name' => 'nullable|string|max:255',
            'additional_positions.*.office_id' => 'nullable|exists:offices,id',
            'additional_positions.*.division_id' => 'nullable|exists:divisions,id',
            'additional_positions.*.unit_id' => 'nullable|exists:units,id',
            'additional_positions.*.subunit_id' => 'nullable|exists:subunits,id',
            'additional_positions.*.class_id' => 'nullable|exists:class,id',
            'additional_positions.*.position_role' => 'nullable|in:none,unit_head,division_head,vp,president',
        ]);

        // Handle checkbox values properly - always set emp_status to active (1)
        $isActive = 1; // Default to active
        $selectedRole = $request->input('position_role', ''); // Default to no role for primary position

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
            'emp_status' => $isActive,
        ]);
        
        // Create the primary position record with role
        \App\Models\EmpPosition::create([
            'employee_id' => $employee->id,
            'position_name' => $request->position_name,
            'office_id' => $request->office_id,
            'division_id' => $request->division_id,
            'unit_id' => $request->unit_id,
            'subunit_id' => $request->subunit_id,
            'class_id' => $request->class_id,
            'is_primary' => true,
            'is_unit_head' => $selectedRole === 'unit_head',
            'is_division_head' => $selectedRole === 'division_head',
            'is_vp' => $selectedRole === 'vp',
            'is_president' => $selectedRole === 'president',
        ]);
        
        // Process additional positions if provided
        if ($request->has('additional_positions')) {
            foreach ($request->additional_positions as $position) {
                if (!empty($position['position_name'])) {
                    $positionRole = $position['position_role'] ?? 'none';
                    \App\Models\EmpPosition::create([
                        'employee_id' => $employee->id,
                        'position_name' => $position['position_name'],
                        'office_id' => $position['office_id'] ?? null,
                        'division_id' => $position['division_id'] ?? null,
                        'unit_id' => $position['unit_id'] ?? null,
                        'subunit_id' => $position['subunit_id'] ?? null,
                        'class_id' => $position['class_id'] ?? null,
                        'is_primary' => false,
                        'is_unit_head' => $positionRole === 'unit_head',
                        'is_division_head' => $positionRole === 'division_head',
                        'is_vp' => $positionRole === 'vp',
                        'is_president' => $positionRole === 'president',
                    ]);
                }
            }
        }

        // Create officer record if any position has a leadership role
        $hasLeadershipRole = $employee->positions()->where(function($query) {
            $query->where('is_unit_head', true)
                  ->orWhere('is_division_head', true)
                  ->orWhere('is_vp', true)
                  ->orWhere('is_president', true);
        })->exists();

        if ($hasLeadershipRole) {
            $primaryPosition = $employee->positions()->where('is_primary', true)->first();
            $officerData = [
                'employee_id' => $employee->id,
                'unit_head' => $primaryPosition && $primaryPosition->is_unit_head,
                'division_head' => $primaryPosition && $primaryPosition->is_division_head,
                'vp' => $primaryPosition && $primaryPosition->is_vp,
                'president' => $primaryPosition && $primaryPosition->is_president,
            ];
            
            Officer::create($officerData);
        }

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
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        // Load all related data
        $employee->load([
            'user',
            'positions.office',
            'positions.division',
            'positions.unit',
            'positions.subunit',
            'positions.class'
        ]);
        
        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee): View
    {
        $employee->load(['user', 'positions']); // Load the user and all position relationships
        
        $offices = Office::all();
        
        // Get related divisions, units, and subunits based on primary position
        $primaryPosition = $employee->positions()->where('is_primary', true)->first();
        $officeId = $primaryPosition ? $primaryPosition->office_id : null;
        $divisionId = $primaryPosition ? $primaryPosition->division_id : null;
        $unitId = $primaryPosition ? $primaryPosition->unit_id : null;
        
        $divisions = $officeId ? Division::where('office_id', $officeId)->get() : collect();
        $units = $divisionId ? Unit::where('division_id', $divisionId)->get() : collect();
        $subunits = $unitId ? Subunit::where('unit_id', $unitId)->get() : collect();
        
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
            'contact_num' => 'nullable|string|max:20',
            'emp_status' => 'required|boolean',
            'position_name' => 'required|string|max:255',
            'office_id' => 'nullable|exists:offices,id',
            'division_id' => 'nullable|exists:divisions,id',
            'unit_id' => 'nullable|exists:units,id',
            'subunit_id' => 'nullable|exists:subunits,id',
            'class_id' => 'nullable|exists:class,id',
            'position_role' => 'nullable|in:none,unit_head,division_head,vp,president',
            'additional_positions' => 'nullable|array',
            'additional_positions.*.id' => 'nullable|exists:emp_positions,id',
            'additional_positions.*.position_name' => 'nullable|string|max:255',
            'additional_positions.*.office_id' => 'nullable|exists:offices,id',
            'additional_positions.*.division_id' => 'nullable|exists:divisions,id',
            'additional_positions.*.unit_id' => 'nullable|exists:units,id',
            'additional_positions.*.subunit_id' => 'nullable|exists:subunits,id',
            'additional_positions.*.class_id' => 'nullable|exists:class,id',
            'additional_positions.*.position_role' => 'nullable|in:none,unit_head,division_head,vp,president',
        ]);

        // Handle status properly
        $isActive = $request->emp_status;
        $selectedRole = $request->input('position_role', ''); // Default to no role for primary position

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
            'contact_num' => $request->contact_num,
            'emp_status' => $isActive,
        ]);
        
        // Update or create the primary position record
        $primaryPosition = $employee->positions()->where('is_primary', true)->first();
        if ($primaryPosition) {
            $primaryPosition->update([
                'position_name' => $request->position_name,
                'office_id' => $request->office_id,
                'division_id' => $request->division_id,
                'unit_id' => $request->unit_id,
                'subunit_id' => $request->subunit_id,
                'class_id' => $request->class_id,
                'is_unit_head' => $selectedRole === 'unit_head',
                'is_division_head' => $selectedRole === 'division_head',
                'is_vp' => $selectedRole === 'vp',
                'is_president' => $selectedRole === 'president',
            ]);
        } else {
            \App\Models\EmpPosition::create([
                'employee_id' => $employee->id,
                'position_name' => $request->position_name,
                'office_id' => $request->office_id,
                'division_id' => $request->division_id,
                'unit_id' => $request->unit_id,
                'subunit_id' => $request->subunit_id,
                'class_id' => $request->class_id,
                'is_primary' => true,
                'is_unit_head' => $selectedRole === 'unit_head',
                'is_division_head' => $selectedRole === 'division_head',
                'is_vp' => $selectedRole === 'vp',
                'is_president' => $selectedRole === 'president',
            ]);
        }
        
        // Handle additional positions if provided
        if ($request->has('additional_positions')) {
            // Get existing additional position IDs
            $existingPositionIds = $employee->positions()->where('is_primary', false)->pluck('id')->toArray();
            
            // Process each additional position
            foreach ($request->additional_positions as $positionData) {
                if (!empty($positionData['position_name'])) {
                    $positionRole = $positionData['position_role'] ?? 'none';
                    
                    if (isset($positionData['id']) && in_array($positionData['id'], $existingPositionIds)) {
                        // Update existing position
                        $position = \App\Models\EmpPosition::find($positionData['id']);
                        if ($position) {
                            $position->update([
                                'position_name' => $positionData['position_name'],
                                'office_id' => $positionData['office_id'] ?? null,
                                'division_id' => $positionData['division_id'] ?? null,
                                'unit_id' => $positionData['unit_id'] ?? null,
                                'subunit_id' => $positionData['subunit_id'] ?? null,
                                'class_id' => $positionData['class_id'] ?? null,
                                'is_unit_head' => $positionRole === 'unit_head',
                                'is_division_head' => $positionRole === 'division_head',
                                'is_vp' => $positionRole === 'vp',
                                'is_president' => $positionRole === 'president',
                            ]);
                        }
                        // Remove from existing IDs array to track which ones were processed
                        $existingPositionIds = array_diff($existingPositionIds, [$positionData['id']]);
                    } else {
                        // Create new position
                        \App\Models\EmpPosition::create([
                            'employee_id' => $employee->id,
                            'position_name' => $positionData['position_name'],
                            'office_id' => $positionData['office_id'] ?? null,
                            'division_id' => $positionData['division_id'] ?? null,
                            'unit_id' => $positionData['unit_id'] ?? null,
                            'subunit_id' => $positionData['subunit_id'] ?? null,
                            'class_id' => $positionData['class_id'] ?? null,
                            'is_primary' => false,
                            'is_unit_head' => $positionRole === 'unit_head',
                            'is_division_head' => $positionRole === 'division_head',
                            'is_vp' => $positionRole === 'vp',
                            'is_president' => $positionRole === 'president',
                        ]);
                    }
                }
            }
            
            // Delete any remaining unprocessed positions (were removed from the form)
            if (!empty($existingPositionIds)) {
                \App\Models\EmpPosition::whereIn('id', $existingPositionIds)->delete();
            }
        } else {
            // No additional positions in request, delete all existing additional positions
            $employee->positions()->where('is_primary', false)->delete();
        }

        // Update or create officer record based on selected role
        $hasLeadershipRole = $employee->positions()->where(function($query) {
            $query->where('is_unit_head', true)
                  ->orWhere('is_division_head', true)
                  ->orWhere('is_vp', true)
                  ->orWhere('is_president', true);
        })->exists();

        if ($employee->officer) {
            if ($hasLeadershipRole) {
                $primaryPosition = $employee->positions()->where('is_primary', true)->first();
                $officerData = [
                    'unit_head' => $primaryPosition && $primaryPosition->is_unit_head,
                    'division_head' => $primaryPosition && $primaryPosition->is_division_head,
                    'vp' => $primaryPosition && $primaryPosition->is_vp,
                    'president' => $primaryPosition && $primaryPosition->is_president,
                ];
                // Update existing officer record
                $employee->officer->update($officerData);
            } else {
                // Delete officer record if no role is assigned
                $employee->officer->delete();
            }
        } else {
            // Create new officer record if a role is assigned
            if ($hasLeadershipRole) {
                $primaryPosition = $employee->positions()->where('is_primary', true)->first();
                $officerData = [
                    'employee_id' => $employee->id,
                    'unit_head' => $primaryPosition && $primaryPosition->is_unit_head,
                    'division_head' => $primaryPosition && $primaryPosition->is_division_head,
                    'vp' => $primaryPosition && $primaryPosition->is_vp,
                    'president' => $primaryPosition && $primaryPosition->is_president,
                ];
                Officer::create($officerData);
            }
        }

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
            // Check for dependent records with detailed counts
            $positionCount = $employee->positions()->count();
            $officerCount = $employee->officer ? 1 : 0;
            $userCount = $employee->user ? 1 : 0;
            
            // If there are dependent records, provide detailed error message
            if ($positionCount > 0 || $officerCount > 0 || $userCount > 0) {
                $message = "Cannot delete employee '{$employee->full_name}' because it has dependent records:";
                
                if ($positionCount > 0) {
                    $message .= "\n- {$positionCount} position(s)";
                }
                
                if ($officerCount > 0) {
                    $message .= "\n- 1 officer record";
                }
                
                if ($userCount > 0) {
                    $message .= "\n- 1 user account";
                }
                
                $message .= "\n\nOptions:";
                $message .= "\n1. Reassign or delete these dependent records first";
                $message .= "\n2. Archive the employee instead (set status to inactive)";
                $message .= "\n\nTo archive, update the employee's status to inactive instead of deleting.";
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'dependencies' => [
                            'positions' => $positionCount,
                            'officer' => $officerCount,
                            'user' => $userCount
                        ]
                    ], 422);
                }
                
                return redirect()->route('admin.employees.index')
                                 ->with('error', $message);
            }
            
            // Safe to delete
            $employeeName = $employee->full_name;
            $employee->delete();
            
            $successMessage = "Employee '{$employeeName}' deleted successfully.";
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }
            
            return redirect()->route('admin.employees.index')
                             ->with('success', $successMessage);
        } catch (\Exception $e) {
            \Log::error('Error deleting employee: ' . $e->getMessage());
            \Log::error('Employee ID: ' . $employee->id . ', Name: ' . $employee->full_name . ', User ID: ' . $employee->user_id);
            
            $errorMessage = 'There was an error deleting the employee: ' . $e->getMessage();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->route('admin.employees.index')
                             ->with('error', $errorMessage);
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