<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;

class MotorpoolAdminController extends Controller
{
    /**
     * Display approved travel orders for the motorpool.
     */
    public function approvedTravelOrders(Request $request): View
    {
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Get travel orders that are approved and ready for motorpool processing
        $query = TravelOrder::where('status', 'approved')
            ->with('employee'); // Eager load employee relationship
        
        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('destination', 'LIKE', "%{$search}%")
                  ->orWhere('purpose', 'LIKE', "%{$search}%")
                  ->orWhereHas('employee', function($q) use ($search) {
                      $q->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Check if this is an AJAX request for partial updates
        if ($request->ajax() || $request->get('ajax')) {
            $travelOrders = $query->orderBy('created_at', 'desc')->get();
            return view('travel-orders.approvals.partials.motorpool-table-rows', compact('travelOrders'))->render();
        }
        
        $travelOrders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('travel-orders.approvals.motorpool-index', compact('travelOrders', 'search'));
    }
}