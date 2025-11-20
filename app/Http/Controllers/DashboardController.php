<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;

class DashboardController extends Controller
{
    /**
     * Display the appropriate dashboard based on user role
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        // If user is not logged in, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Determine which dashboard to show based on user role
        if ($user->isMotorpoolAdmin()) {
            return view('dashboards.motorpool-admin');
        } elseif ($user->isAdmin()) {
            return view('dashboards.admin');
        } elseif ($user->isDriver()) {
            return view('dashboards.driver');
        } elseif ($user->isEmployee()) {
            // Load the employee relationship
            $user->load('employee');
            
            // Check if employee is a head (division head, VP, or unit head)
            $employee = $user->employee;
            if ($employee) {
                // Get travel order counts for the employee
                $employeeId = $user->employee->id;
                $pendingCount = TravelOrder::where('employee_id', $employeeId)
                    ->where(function ($query) {
                        $query->where('divisionhead_approved', 0)
                              ->orWhereNull('divisionhead_approved')
                              ->orWhere(function ($q) {
                                  $q->where('divisionhead_approved', 1)
                                    ->whereNull('vp_approved');
                              });
                    })
                    ->count();
                    
                $approvedCount = TravelOrder::where('employee_id', $employeeId)
                    ->where('divisionhead_approved', 1)
                    ->where('vp_approved', 1)
                    ->count();
                    
                $totalCount = TravelOrder::where('employee_id', $employeeId)->count();
                
                // Determine which dashboard to show based on specific role
                if ($employee->is_president) {
                    return view('dashboards.president', compact('user', 'pendingCount', 'approvedCount', 'totalCount'));
                } elseif ($employee->is_vp) {
                    return view('dashboards.vp', compact('user', 'pendingCount', 'approvedCount', 'totalCount'));
                } elseif ($employee->is_divisionhead) {
                    return view('dashboards.divisionhead', compact('user', 'pendingCount', 'approvedCount', 'totalCount'));
                } elseif ($employee->is_head) {
                    return view('dashboards.head', compact('user', 'pendingCount', 'approvedCount', 'totalCount'));
                } else {
                    // Regular employee dashboard
                    return view('dashboards.employee', compact('user', 'pendingCount', 'approvedCount', 'totalCount'));
                }
            } else {
                // Regular employee dashboard
                // Get travel order counts for the employee
                $employeeId = $user->employee->id;
                $pendingCount = TravelOrder::where('employee_id', $employeeId)
                    ->where(function ($query) {
                        $query->where('divisionhead_approved', 0)
                              ->orWhereNull('divisionhead_approved')
                              ->orWhere(function ($q) {
                                  $q->where('divisionhead_approved', 1)
                                    ->whereNull('vp_approved');
                              });
                    })
                    ->count();
                    
                $approvedCount = TravelOrder::where('employee_id', $employeeId)
                    ->where('divisionhead_approved', 1)
                    ->where('vp_approved', 1)
                    ->count();
                    
                $totalCount = TravelOrder::where('employee_id', $employeeId)->count();
                
                return view('dashboards.employee', compact('user', 'pendingCount', 'approvedCount', 'totalCount'));
            }
        }

        // Default dashboard if no role matches
        return view('dashboard');
    }
    
    /**
     * Get travel orders requiring approval based on employee's role (for dashboard)
     */
    private function getPendingApprovals($employee)
    {
        $query = TravelOrder::with('employee');
        
        // If employee is a division head, show orders from their division pending division head approval
        if ($employee->is_divisionhead && $employee->division_id) {
            $query->whereHas('employee', function ($q) use ($employee) {
                $q->where('division_id', $employee->division_id);
            })->where('divisionhead_approved', 0)->whereNull('divisionhead_declined');
        }
        // If employee is a VP, show orders pending VP approval
        elseif ($employee->is_vp) {
            $query->where('divisionhead_approved', 1)->whereNull('vp_approved')->whereNull('vp_declined');
        }
        // If employee is a unit head, show orders from their unit pending head approval
        elseif ($employee->is_head && $employee->unit_id) {
            $query->whereHas('employee', function ($q) use ($employee) {
                $q->where('unit_id', $employee->unit_id);
            })->whereNull('head_approved')->whereNull('head_disapproved');
        }
        // If none of the above, return empty collection
        else {
            return collect();
        }
        
        return $query->orderBy('created_at', 'desc')->limit(10)->get();
    }
    
    /**
     * Get travel orders requiring approval based on employee's role (for sidebar)
     */
    public static function getPendingApprovalsForSidebar($employee)
    {
        $query = TravelOrder::with('employee');
        
        // Log the employee information for debugging
        \Log::info('Getting pending approvals for employee:', [
            'employee_id' => $employee->id,
            'is_head' => $employee->is_head,
            'is_divisionhead' => $employee->is_divisionhead,
            'is_vp' => $employee->is_vp,
            'unit_id' => $employee->unit_id,
            'division_id' => $employee->division_id
        ]);
        
        // If employee is a division head, show orders from their division pending division head approval
        if ($employee->is_divisionhead && $employee->division_id) {
            \Log::info('Employee is division head, filtering by division_id:', ['division_id' => $employee->division_id]);
            $query->whereHas('employee', function ($q) use ($employee) {
                $q->where('division_id', $employee->division_id);
            })->where('divisionhead_approved', 0)->whereNull('divisionhead_declined');
        }
        // If employee is a VP, show orders pending VP approval
        elseif ($employee->is_vp) {
            \Log::info('Employee is VP, filtering by VP approval status');
            $query->where('divisionhead_approved', 1)->whereNull('vp_approved')->whereNull('vp_declined');
        }
        // If employee is a unit head, show orders from employees in their unit pending head approval
        elseif ($employee->is_head && $employee->unit_id) {
            \Log::info('Employee is unit head, filtering by unit_id:', ['unit_id' => $employee->unit_id]);
            $query->whereHas('employee', function ($q) use ($employee) {
                $q->where('unit_id', $employee->unit_id);
            })->whereNull('head_approved')->whereNull('head_disapproved');
        }
        // If none of the above, return empty collection
        else {
            \Log::info('Employee is not a head, division head, or VP');
            return collect();
        }
        
        $results = $query->get();
        \Log::info('Found pending approvals:', ['count' => $results->count()]);
        
        return $results;
    }
}