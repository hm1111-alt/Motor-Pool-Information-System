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
        // Get all approved travel orders
        $travelOrders = TravelOrder::where('vp_approved', true)
            ->orderBy('vp_approved_at', 'desc')
            ->get();
        
        return view('travel-orders.approvals.motorpool-index', compact('travelOrders'));
    }
}