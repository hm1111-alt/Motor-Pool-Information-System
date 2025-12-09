<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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
                // Determine which dashboard to show based on specific role
                if ($employee->is_president) {
                    return view('dashboards.president');
                } elseif ($employee->is_vp) {
                    return view('dashboards.vp');
                } elseif ($employee->is_divisionhead) {
                    return view('dashboards.divisionhead');
                } elseif ($employee->is_head) {
                    return view('dashboards.head');
                } else {
                    // Regular employee dashboard
                    return view('dashboards.employee');
                }
            } else {
                // Regular employee dashboard
                return view('dashboards.employee');
            }
        }

        // Default dashboard if no role matches
        return view('dashboard');
    }
    

}