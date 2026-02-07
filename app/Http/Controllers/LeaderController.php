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
        
        return view('admin.leaders.index', compact('president'));
    }
    
    public function offices()
    {
        // Check if user is admin (not motorpool admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        
        // Get all offices with their VPs and related counts
        $offices = Office::with(['positions' => function($query) {
            $query->whereHas('employee.officer', function ($officerQuery) {
                            $officerQuery->where('vp', true);
                        })->with('employee.user');
        }])
        ->with(['divisions' => function($query) {
            $query->withCount('units');
        }])
        ->withCount(['divisions', 'employees'])
        ->get();
        
        // Add units count manually by calculating from divisions
        foreach ($offices as $office) {
            $office->units_count = $office->divisions->sum('units_count');
        }
        
        return view('admin.leaders.offices', compact('offices'));
    }
    
    public function showOffice(Office $office)
    {
        // Check if user is admin (not motorpool admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        
        // Load office with divisions and their heads
        $office->load([
            'divisions.positions.employee.officer',
            'positions.employee.officer'
        ]);
        
        // Get VP for this office
        $vp = $office->employees()->whereHas('officer', function ($officerQuery) {
            $officerQuery->where('vp', true);
        })->first();
        
        return view('admin.leaders.office-show', compact('office', 'vp'));
    }
    
    public function showDivision(Division $division)
    {
        // Check if user is admin (not motorpool admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        
        // Load division with units and their heads, including subunits for count
        $division->load([
            'units.positions.employee.officer',
            'units.subunits',  // Add this to load subunits for each unit
            'positions.employee.officer',
            'office'
        ]);
        
        // Load counts separately - divisions have units, but subunits are related to units
        $division->loadCount(['units']);
        
        // Count subunits manually by aggregating from units
        $division->subunits_count = $division->units->sum(function($unit) {
            return $unit->subunits->count();
        });
        
        // Count employees related to this division through positions
        $division->employees_count = $division->positions->pluck('employee_id')->unique()->count();
        
        // Get Division Head
        $divisionHead = $division->employees()->whereHas('officer', function ($officerQuery) {
            $officerQuery->where('division_head', true);
        })->first();
        
        return view('admin.leaders.division-show', compact('division', 'divisionHead'));
    }
    
    public function showUnit(Unit $unit)
    {
        // Check if user is admin (not motorpool admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        
        // Load unit with its head
        $unit->load([
            'positions.employee.officer',
            'division.office'
        ]);
        
        // Load counts separately
        $unit->loadCount(['subunits']);
        
        // Count employees related to this unit through positions
        $unit->employees_count = $unit->positions->pluck('employee_id')->unique()->count();
        
        // Get Unit Head
        $unitHead = $unit->employees()->whereHas('officer', function ($officerQuery) {
            $officerQuery->where('unit_head', true);
        })->first();
        
        return view('admin.leaders.unit-show', compact('unit', 'unitHead'));
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
        
        return view('admin.leaders.edit', compact('type', 'employee', 'organization', 'employees'));
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
            if ($employee && $employee->officer && $employee->officer->president) {
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
                    // Update employee's primary position to be in this office
                    if ($request->organization_id) {
                        $primaryPosition = $employee->positions()->where('is_primary', true)->first();
                        if ($primaryPosition) {
                            $primaryPosition->update([
                                'office_id' => $request->organization_id
                            ]);
                        }
                    }
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
                    // Update employee's primary position to be in this division
                    if ($request->organization_id) {
                        $primaryPosition = $employee->positions()->where('is_primary', true)->first();
                        if ($primaryPosition) {
                            $primaryPosition->update([
                                'division_id' => $request->organization_id
                            ]);
                        }
                    }
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
                    // Update employee's primary position to be in this unit
                    if ($request->organization_id) {
                        $primaryPosition = $employee->positions()->where('is_primary', true)->first();
                        if ($primaryPosition) {
                            $primaryPosition->update([
                                'unit_id' => $request->organization_id
                            ]);
                        }
                    }
                }
                break;
        }
        
        // Redirect back to appropriate page
        if ($request->type === 'vp' && $request->organization_id) {
            return redirect()->route('admin.leaders.office.show', $request->organization_id)
                           ->with('success', 'Leadership role updated successfully.');
        } elseif ($request->type === 'division_head' && $request->organization_id) {
            return redirect()->route('admin.leaders.division.show', $request->organization_id)
                           ->with('success', 'Leadership role updated successfully.');
        } elseif ($request->type === 'unit_head' && $request->organization_id) {
            return redirect()->route('admin.leaders.unit.show', $request->organization_id)
                           ->with('success', 'Leadership role updated successfully.');
        }
        
        return redirect()->route('admin.leaders.index')->with('success', 'Leadership role updated successfully.');
    }
}