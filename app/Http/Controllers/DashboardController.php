<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
            return view('dashboards.employee', compact('user'));
        }

        // Default dashboard if no role matches
        return view('dashboard');
    }
}