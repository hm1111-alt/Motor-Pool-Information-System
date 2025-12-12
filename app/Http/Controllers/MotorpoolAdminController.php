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
        // Get all approved travel orders (either VP approved for regular employees/heads, President approved for division heads, President approved for VPs, or President self-created)
        $travelOrders = TravelOrder::where(function ($query) {
                // Regular employees and heads approved by VP
                $query->whereHas('employee', function ($subQuery) {
                    $subQuery->where('is_divisionhead', 0)
                              ->where('is_vp', 0)
                              ->where('is_president', 0)
                              ->orWhereNull('is_divisionhead')
                              ->orWhereNull('is_vp')
                              ->orWhereNull('is_president');
                })->where('vp_approved', true)
                // Division heads approved by President
                ->orWhereHas('employee', function ($subQuery) {
                    $subQuery->where('is_divisionhead', 1)
                              ->where('is_vp', 0)
                              ->where('is_president', 0);
                })->where('president_approved', true)
                // VPs approved by President
                ->orWhereHas('employee', function ($subQuery) {
                    $subQuery->where('is_vp', 1)
                              ->where('is_president', 0);
                })->where('president_approved', true)
                // Presidents self-created (automatically approved)
                ->orWhereHas('employee', function ($subQuery) {
                    $subQuery->where('is_president', 1);
                })->where('president_approved', true);
            })
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return view('travel-orders.approvals.motorpool-index', compact('travelOrders'));
    }
}