<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelOrder;
use Illuminate\View\View;

class MotorpoolAdminTravelOrderController extends Controller
{
    /**
     * Display a listing of all approved travel orders.
     */
    public function index(): View
    {
        $travelOrders = TravelOrder::with(['employee', 'employee.user'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('motorpool-admin.travel-orders.index', compact('travelOrders'));
    }

    /**
     * Display the specified travel order.
     */
    public function show(TravelOrder $travelOrder): View
    {
        $travelOrder->load(['employee', 'employee.user']);
        
        return view('motorpool-admin.travel-orders.show', compact('travelOrder'));
    }
}