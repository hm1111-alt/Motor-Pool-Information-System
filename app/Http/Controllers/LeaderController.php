<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Division;
use App\Models\Unit;
use App\Models\Subunit;
use Illuminate\Support\Facades\Auth;

class LeaderController extends Controller
{
    public function index()
    {
        // Check if user is admin (not motorpool admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        
        // Get all VPs
        $vps = Employee::where('is_vp', true)->get();
        
        // Get all Division Heads
        $divisionHeads = Employee::where('is_divisionhead', true)->get();
        
        // Get all employees for selection
        $employees = Employee::where('emp_status', 1)->get();
        
        // Get organizational structure
        $offices = Office::all();
        $divisions = Division::all();
        $units = Unit::all();
        $subunits = Subunit::all();
        
        return view('admin.leaders.index', compact('vps', 'divisionHeads', 'employees', 'offices', 'divisions', 'units', 'subunits'));
    }
    
    public function edit($type, $id = null)
    {
        // Check if user is admin (not motorpool admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        
        $employee = null;
        if ($id) {
            $employee = Employee::find($id);
        }
        
        $employees = Employee::where('emp_status', 1)->get();
        
        return view('admin.leaders.edit', compact('type', 'employee', 'employees'));
    }
    
    public function update(Request $request)
    {
        // Check if user is admin (not motorpool admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        
        $request->validate([
            'type' => 'required|string',
            'employee_id' => 'nullable|exists:employees,id',
        ]);
        
        // Reset the leadership role for the type
        switch ($request->type) {
            case 'president':
                // For simplicity, we'll just update the view
                break;
            case 'vp':
                // Reset all VPs
                Employee::where('is_vp', true)->update(['is_vp' => false]);
                // Set new VP if selected
                if ($request->employee_id) {
                    $employee = Employee::find($request->employee_id);
                    $employee->is_vp = true;
                    $employee->save();
                }
                break;
            case 'division_head':
                // Reset all Division Heads
                Employee::where('is_divisionhead', true)->update(['is_divisionhead' => false]);
                // Set new Division Head if selected
                if ($request->employee_id) {
                    $employee = Employee::find($request->employee_id);
                    $employee->is_divisionhead = true;
                    $employee->save();
                }
                break;
        }
        
        return redirect()->route('admin.leaders.index')->with('success', 'Leadership role updated successfully.');
    }
}