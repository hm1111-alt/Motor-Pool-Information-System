<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Division;
use App\Models\Unit;
use App\Models\Subunit;
use App\Models\ClassModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $employee = $user->employee;
        
        if ($employee) {
            $employee->load(['user', 'positions']); // Load the user and all position relationships
            
            $offices = Office::all();
            
            // Pre-load all cascading data to avoid AJAX issues
            $cascadingData = [
                'divisions' => [],
                'units' => [],
                'subunits' => []
            ];
            
            // Load divisions for all offices
            foreach ($offices as $office) {
                $divisions = Division::where('office_id', $office->id)->get();
                $cascadingData['divisions'][$office->id] = [];
                foreach ($divisions as $division) {
                    $cascadingData['divisions'][$office->id][] = [
                        'id_division' => $division->id_division,
                        'division_name' => $division->division_name,
                        'office_id' => $division->office_id
                    ];
                }
            }
            
            // Load units for all divisions
            $allDivisions = Division::all();
            foreach ($allDivisions as $division) {
                $units = Unit::where('unit_division', $division->id_division)->get();
                $cascadingData['units'][$division->id_division] = [];
                foreach ($units as $unit) {
                    $cascadingData['units'][$division->id_division][] = [
                        'id_unit' => $unit->id_unit,
                        'unit_name' => $unit->unit_name,
                        'unit_division' => $unit->unit_division
                    ];
                }
            }
            
            // Load subunits for all units
            $allUnits = Unit::all();
            foreach ($allUnits as $unit) {
                $subunits = Subunit::where('unit_id', $unit->id_unit)->get();
                $cascadingData['subunits'][$unit->id_unit] = [];
                foreach ($subunits as $subunit) {
                    $cascadingData['subunits'][$unit->id_unit][] = [
                        'id_subunit' => $subunit->id_subunit,
                        'subunit_name' => $subunit->subunit_name,
                        'unit_id' => $subunit->unit_id
                    ];
                }
            }
            
            // Get related divisions, units, and subunits based on primary position
            $primaryPosition = $employee->positions()->where('is_primary', true)->first();
            
            // If no primary position exists, try to find the first position
            if (!$primaryPosition) {
                $primaryPosition = $employee->positions()->first();
            }
            
            $officeId = $primaryPosition ? $primaryPosition->office_id : null;
            $divisionId = $primaryPosition ? $primaryPosition->division_id : null;
            $unitId = $primaryPosition ? $primaryPosition->unit_id : null;
            
            // Load divisions, units, and subunits for the employee's current position
            $divisions = $officeId ? Division::where('office_id', $officeId)->get()->map(function($item) {
                return [
                    'id_division' => $item->id_division,
                    'division_name' => $item->division_name,
                    'office_id' => $item->office_id
                ];
            })->toArray() : [];
            
            $units = $divisionId ? Unit::where('unit_division', $divisionId)->get()->map(function($item) {
                return [
                    'id_unit' => $item->id_unit,
                    'unit_name' => $item->unit_name,
                    'unit_division' => $item->unit_division
                ];
            })->toArray() : [];
            
            $subunits = $unitId ? Subunit::where('unit_id', $unitId)->get()->map(function($item) {
                return [
                    'id_subunit' => $item->id_subunit,
                    'subunit_name' => $item->subunit_name,
                    'unit_id' => $item->unit_id
                ];
            })->toArray() : [];
            
            $classes = ClassModel::all();
            
            // Pass all positions to the view
            $allPositions = $employee->positions;
            
            // Load related data for each position (similar to admin controller)
            foreach ($allPositions as $position) {
                // Load office
                if ($position->office_id) {
                    $position->office = Office::find($position->office_id);
                }
                
                // Load division
                if ($position->division_id) {
                    $position->division = Division::find($position->division_id);
                }
                
                // Load unit
                if ($position->unit_id) {
                    $position->unit = Unit::find($position->unit_id);
                }
                
                // Load subunit
                if ($position->subunit_id) {
                    $position->subunit = Subunit::find($position->subunit_id);
                }
                
                // Load class
                if ($position->class_id) {
                    $position->class = ClassModel::find($position->class_id);
                }
            }
            
            return view('profile.edit', [
                'user' => $user,
                'employee' => $employee,
                'offices' => $offices,
                'divisions' => $divisions,
                'units' => $units,
                'subunits' => $subunits,
                'classes' => $classes,
                'cascadingData' => $cascadingData,
                'primaryPosition' => $primaryPosition,
                'allPositions' => $allPositions
            ]);
        } else {
            return view('profile.edit', [
                'user' => $user,
                'employee' => null,
                'offices' => null,
                'divisions' => null,
                'units' => null,
                'subunits' => null,
                'classes' => null,
                'cascadingData' => null
            ]);
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $employee = $user->employee;
        
        // Validate employee-specific fields if employee exists
        if ($employee) {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_initial' => 'nullable|string|max:10',
                'ext_name' => 'nullable|string|max:10',
                'sex' => 'required|string|in:M,F',
                'prefix' => 'nullable|string|max:10',
                'contact_num' => 'nullable|string|max:20',
                'position_name' => 'required|string|max:255',
                'office_id' => 'nullable|exists:offices,id',
                'division_id' => 'nullable|exists:lib_divisions,id_division',
                'unit_id' => 'nullable|exists:lib_units,id_unit',
                'subunit_id' => 'nullable|exists:lib_subunits,id_subunit',
                'class_id' => 'nullable|exists:lib_class,id_class',
            ]);
            
            // Update the user
            $user->fill($request->validated());
            
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            
            $user->save();
            
            // Update employee data
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
            ]);
            
            // Update primary position
            $primaryPosition = $employee->positions()->where('is_primary', true)->first();
            if ($primaryPosition) {
                $primaryPosition->update([
                    'position_name' => $request->position_name,
                    'office_id' => $request->office_id,
                    'division_id' => $request->division_id,
                    'unit_id' => $request->unit_id,
                    'subunit_id' => $request->subunit_id,
                    'class_id' => $request->class_id,
                ]);
            } else {
                // If no primary position exists, create one or update the first position as primary
                $firstPosition = $employee->positions()->first();
                if ($firstPosition) {
                    $firstPosition->update([
                        'position_name' => $request->position_name,
                        'office_id' => $request->office_id,
                        'division_id' => $request->division_id,
                        'unit_id' => $request->unit_id,
                        'subunit_id' => $request->subunit_id,
                        'class_id' => $request->class_id,
                        'is_primary' => true,
                    ]);
                } else {
                    // Create the first position as primary if none exist
                    \App\Models\EmpPosition::create([
                        'employee_id' => $employee->id,
                        'position_name' => $request->position_name,
                        'office_id' => $request->office_id,
                        'division_id' => $request->division_id,
                        'unit_id' => $request->unit_id,
                        'subunit_id' => $request->subunit_id,
                        'class_id' => $request->class_id,
                        'is_primary' => true,
                    ]);
                }
            }
            
            // Handle additional positions
            if ($request->has('additional_positions')) {
                foreach ($request->additional_positions as $positionData) {
                    if (isset($positionData['id']) && !empty($positionData['id'])) {
                        // Update existing position
                        $existingPosition = \App\Models\EmpPosition::find($positionData['id']);
                        if ($existingPosition && $existingPosition->employee_id == $employee->id) {
                            $existingPosition->update([
                                'position_name' => $positionData['position_name'] ?? null,
                                'office_id' => $positionData['office_id'] ?? null,
                                'division_id' => $positionData['division_id'] ?? null,
                                'unit_id' => $positionData['unit_id'] ?? null,
                                'subunit_id' => $positionData['subunit_id'] ?? null,
                                'class_id' => $positionData['class_id'] ?? null,
                                'is_primary' => false, // Additional positions are never primary
                            ]);
                        }
                    } else {
                        // Create new position
                        \App\Models\EmpPosition::create([
                            'employee_id' => $employee->id,
                            'position_name' => $positionData['position_name'] ?? null,
                            'office_id' => $positionData['office_id'] ?? null,
                            'division_id' => $positionData['division_id'] ?? null,
                            'unit_id' => $positionData['unit_id'] ?? null,
                            'subunit_id' => $positionData['subunit_id'] ?? null,
                            'class_id' => $positionData['class_id'] ?? null,
                            'is_primary' => false, // Additional positions are never primary
                        ]);
                    }
                }
            }
            
            // Remove positions that weren't included in the request (except the primary position)
            $allPositionIds = [];
            if ($primaryPosition) {
                $allPositionIds[] = $primaryPosition->id;
            }
            if ($request->has('additional_positions')) {
                foreach ($request->additional_positions as $positionData) {
                    if (isset($positionData['id']) && !empty($positionData['id'])) {
                        $allPositionIds[] = $positionData['id'];
                    }
                }
            }
            
            if (!empty(array_filter($allPositionIds))) { // Make sure we have valid IDs to exclude
                \App\Models\EmpPosition::where('employee_id', $employee->id)
                    ->where('is_primary', false)
                    ->whereNotIn('id', array_filter($allPositionIds))
                    ->delete();
            }
        } else {
            // For users without employees, just update user data
            $request->user()->fill($request->validated());
            
            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }
            
            $request->user()->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
