<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;

class PresidentTravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders for presidential approval.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get travel orders that need presidential approval
        // These are:
        // 1. Travel orders from division heads that have been approved by VP
        // 2. Travel orders from VPs (no prior approval needed)
        $query = TravelOrder::where(function ($q) {
                    // Division head travel orders approved by VP
                    $q->whereHas('employee', function ($subQuery) {
                        $subQuery->where('is_divisionhead', 1);
                    })->where('vp_approved', true)
                    // VP travel orders (no prior approval needed)
                    ->orWhereHas('employee', function ($subQuery) {
                        $subQuery->where('is_vp', 1);
                    });
                })
                ->where('president_approved', null);
        
        switch ($tab) {
            case 'approved':
                $query->where('president_approved', true);
                break;
            case 'cancelled':
                $query->where('president_approved', false);
                break;
            case 'pending':
            default:
                $query->where('president_approved', null);
                break;
        }
        
        $travelOrders = $query->orderByRaw('(CASE WHEN travel_orders.employee_id IN (SELECT id FROM employees WHERE is_vp = 1) THEN created_at ELSE vp_approved_at END) DESC')->get();
        
        return view('travel-orders.approvals.president-index', compact('travelOrders', 'tab'));
    }

    /**
     * Approve a travel order.
     */
    public function approve(TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // No office restriction for presidents - they can approve any division head or VP travel order
        
        // Ensure the travel order has been approved by VP (for division heads) or is from a VP
        if (!$travelOrder->employee->is_vp && !$travelOrder->vp_approved) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved by president
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Approve the travel order
        $travelOrder->update([
            'president_approved' => true,
            'president_approved_at' => now(),
            'status' => 'approved',
        ]);

        return redirect()->back()
            ->with('success', 'Travel order approved successfully.');
    }

    /**
     * Reject a travel order.
     */
    public function reject(TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // No office restriction for presidents - they can reject any division head or VP travel order
        
        // Ensure the travel order has been approved by VP (for division heads) or is from a VP
        if (!$travelOrder->employee->is_vp && !$travelOrder->vp_approved) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved by president
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Reject the travel order
        $travelOrder->update([
            'president_approved' => false,
            'president_approved_at' => now(),
            'status' => 'cancelled',
        ]);

        return redirect()->back()
            ->with('success', 'Travel order rejected successfully.');
    }
}