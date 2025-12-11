<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;

class MotorpoolAdminController extends Controller
{
    /**
     * Display a listing of approved travel orders.
     */
    public function approvedTravelOrders(): View
    {
        // Get all approved travel orders (either VP approved for regular employees/heads or President approved for division heads)
        $travelOrders = TravelOrder::where(function ($query) {
                // Regular employees and heads approved by VP
                $query->whereHas('employee', function ($subQuery) {
                    $subQuery->where('is_divisionhead', 0)
                              ->orWhereNull('is_divisionhead');
                })->where('vp_approved', true)
                // Division heads approved by President
                ->orWhereHas('employee', function ($subQuery) {
                    $subQuery->where('is_divisionhead', 1);
                })->where('president_approved', true);
            })
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return view('travel-orders.approvals.motorpool-index', compact('travelOrders'));
    }
}