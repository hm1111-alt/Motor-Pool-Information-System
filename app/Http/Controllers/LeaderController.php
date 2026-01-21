<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Division;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

class LeaderController extends Controller
{
    public function index()
    {
        // Check if user is admin (not motorpool admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        
        // Get President
        $president = Employee::whereHas('officer', function ($query) {
                    $query->where('president', true);
                })->first();
        
        // Get all offices with their VPs
        $offices = Office::with(['employees' => function($query) {
            $query->whereHas('officer', function ($officerQuery) {
                            $officerQuery->where('vp', true);
                        });
        }])->get();
        
        // Get all divisions with their Division Heads
        $divisions = Division::with(['employees' => function($query) {
            $query->whereHas('officer', function ($officerQuery) {
                            $officerQuery->where('division_head', true);
                        });
        }])->get();
        
        // Get all units with their Unit Heads
        $units = Unit::with(['employees' => function($query) {
            $query->whereHas('officer', function ($officerQuery) {
                            $officerQuery->where('unit_head', true);
                        });
        }])->get();
        
        return view('admin.leaders.index', compact('president', 'offices', 'divisions', 'units'));
    }
    
    public function edit($type, $id = null)
    {
        // Check if user is admin (not motorpool admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        
        $employee = null;
        $organization = null;
        
        if ($id) {
            if ($type === 'vp') {
                $organization = Office::find($id);
            } elseif ($type === 'division_head') {
                $organization = Division::find($id);
            } elseif ($type === 'unit_head') {
                $organization = Unit::find($id);
            }
        }
        
        // Get current employee for this role if exists
        if ($organization) {
            if ($type === 'vp') {
                $employee = $organization->employees()->whereHas('officer', function ($officerQuery) {
                                $officerQuery->where('vp', true);
                            })->first();
            } elseif ($type === 'division_head') {
                $employee = $organization->employees()->whereHas('officer', function ($officerQuery) {
                                $officerQuery->where('division_head', true);
                            })->first();
            } elseif ($type === 'unit_head') {
                $employee = $organization->employees()->whereHas('officer', function ($officerQuery) {
                                $officerQuery->where('unit_head', true);
                            })->first();
            }
        }
        
        // Get all employees for selection (both active and inactive)
        $employees = Employee::all();
        
        // Get organizational structure
        $offices = Office::all();
        $divisions = Division::all();
        $units = Unit::all();
        
        return view('admin.leaders.edit', compact('type', 'employee', 'organization', 'employees', 'offices', 'divisions', 'units'));
    }
    
    public function update(Request $request)
    {
        // Check if user is admin (not motorpool admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        
        $request->validate([
            'type' => 'required|string',
            'organization_id' => 'nullable|integer',
            'employee_id' => 'nullable|exists:employees,id',
        ]);
        
        // Additional validation to prevent assigning President as VP
        if ($request->type === 'vp' && $request->employee_id) {
            $employee = Employee::find($request->employee_id);
            if ($employee && $employee->is_president) {
                return redirect()->back()->with('error', 'Cannot assign the University President as a Vice President.');
            }
        }
        
        // Additional validation to prevent assigning VP to President's office
        if ($request->type === 'vp' && $request->organization_id) {
            $office = Office::find($request->organization_id);
            if ($office && strpos($office->office_name, 'Office of the University President') !== false) {
                return redirect()->back()->with('error', 'Cannot assign a Vice President to the Office of the University President.');
            }
        }
        
        // Handle the leadership role assignment
        switch ($request->type) {
            case 'president':
                // Reset current president
                // Update the officer records instead of the employee directly
                $currentPresident = Employee::whereHas('officer', function ($query) {
                    $query->where('president', true);
                })->first();
                
                if ($currentPresident && $currentPresident->officer) {
                    $currentPresident->officer->update([
                        'president' => false
                    ]);
                }
                // Set new president if selected
                if ($request->employee_id) {
                    $employee = Employee::find($request->employee_id);
                    // Create or update officer record for president
                    $officer = $employee->officer ?? $employee->officer()->create([
                        'employee_id' => $employee->id,
                        'unit_head' => false,
                        'division_head' => false,
                        'vp' => false,
                        'president' => true,
                    ]);
                    
                    if ($officer) {
                        $officer->update([
                            'president' => true
                        ]);
                    }
                }
                break;
                
            case 'vp':
                // Remove VP role from current employee for this office
                if ($request->organization_id) {
                    $office = Office::find($request->organization_id);
                    if ($office) {
                        $currentVp = $office->employees()->whereHas('officer', function ($officerQuery) {
                            $officerQuery->where('vp', true);
                        })->first();
                        if ($currentVp && $currentVp->officer) {
                            $currentVp->officer->update([
                                'vp' => false
                            ]);
                        }
                    }
                }
                // Set new VP if selected
                if ($request->employee_id) {
                    $employee = Employee::find($request->employee_id);
                    // Create or update officer record for VP
                    $officer = $employee->officer ?? $employee->officer()->create([
                        'employee_id' => $employee->id,
                        'unit_head' => false,
                        'division_head' => false,
                        'vp' => true,
                        'president' => false,
                    ]);
                    
                    if ($officer) {
                        $officer->update([
                            'vp' => true
                        ]);
                    }
                    // Assign to office if organization_id is provided
                    if ($request->organization_id) {
                        $employee->office_id = $request->organization_id;
                    }
                    $employee->save();
                }
                break;
                
            case 'division_head':
                // Remove Division Head role from current employee for this division
                if ($request->organization_id) {
                    $division = Division::find($request->organization_id);
                    if ($division) {
                        $currentDivisionHead = $division->employees()->whereHas('officer', function ($officerQuery) {
                            $officerQuery->where('division_head', true);
                        })->first();
                        if ($currentDivisionHead && $currentDivisionHead->officer) {
                            $currentDivisionHead->officer->update([
                                'division_head' => false
                            ]);
                        }
                    }
                }
                // Set new Division Head if selected
                if ($request->employee_id) {
                    $employee = Employee::find($request->employee_id);
                    // Create or update officer record for Division Head
                    $officer = $employee->officer ?? $employee->officer()->create([
                        'employee_id' => $employee->id,
                        'unit_head' => false,
                        'division_head' => true,
                        'vp' => false,
                        'president' => false,
                    ]);
                    
                    if ($officer) {
                        $officer->update([
                            'division_head' => true
                        ]);
                    }
                    // Assign to division if organization_id is provided
                    if ($request->organization_id) {
                        $employee->division_id = $request->organization_id;
                    }
                    $employee->save();
                }
                break;
                
            case 'unit_head':
                // Remove Unit Head role from current employee for this unit
                if ($request->organization_id) {
                    $unit = Unit::find($request->organization_id);
                    if ($unit) {
                        $currentUnitHead = $unit->employees()->whereHas('officer', function ($officerQuery) {
                            $officerQuery->where('unit_head', true);
                        })->first();
                        if ($currentUnitHead && $currentUnitHead->officer) {
                            $currentUnitHead->officer->update([
                                'unit_head' => false
                            ]);
                        }
                    }
                }
                // Set new Unit Head if selected
                if ($request->employee_id) {
                    $employee = Employee::find($request->employee_id);
                    // Create or update officer record for Unit Head
                    $officer = $employee->officer ?? $employee->officer()->create([
                        'employee_id' => $employee->id,
                        'unit_head' => true,
                        'division_head' => false,
                        'vp' => false,
                        'president' => false,
                    ]);
                    
                    if ($officer) {
                        $officer->update([
                            'unit_head' => true
                        ]);
                    }
                    // Assign to unit if organization_id is provided
                    if ($request->organization_id) {
                        $employee->unit_id = $request->organization_id;
                    }
                    $employee->save();
                }
                break;
        }
        
        return redirect()->route('admin.leaders.index')->with('success', 'Leadership role updated successfully.');
    }
}