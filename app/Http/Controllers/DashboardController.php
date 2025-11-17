<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;

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

        // Default dashboard if no role matches
        return view('dashboard');
    }
}