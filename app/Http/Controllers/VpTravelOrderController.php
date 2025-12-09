<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;

class VpTravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders for VP approval with tab support.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Build the query based on the selected tab
        $query = TravelOrder::whereHas('employee', function ($query) use ($employee) {
                $query->where('office_id', $employee->office_id)
                      ->where(function ($query) {
                          $query->where('is_president', 0)
                                ->orWhereNull('is_president');
                      });
            })
            ->where('head_approved', true);
        
        switch ($tab) {
            case 'approved':
                $query->where('vp_approved', true);
                break;
            case 'cancelled':
                $query->where('vp_approved', false);
                break;
            case 'pending':
            default:
                $query->where('vp_approved', null);
                break;
        }
        
        $travelOrders = $query->orderBy('head_approved_at', 'desc')->get();
        
        return view('travel-orders.approvals.vp-index', compact('travelOrders', 'tab'));
    }

    /**
     * Approve a travel order.
     */
    public function approve(TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Ensure the VP can only approve travel orders from their office
        if ($travelOrder->employee->office_id !== $employee->office_id) {
            abort(403);
        }
        
        // Ensure the travel order has been approved by head
        if (!$travelOrder->head_approved) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved by VP
        if ($travelOrder->vp_approved) {
            abort(403);
        }
        
        // Approve the travel order
        $travelOrder->update([
            'vp_approved' => true,
            'vp_approved_at' => now(),
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
        
        // Ensure the VP can only reject travel orders from their office
        if ($travelOrder->employee->office_id !== $employee->office_id) {
            abort(403);
        }
        
        // Ensure the travel order has been approved by head
        if (!$travelOrder->head_approved) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved by VP
        if ($travelOrder->vp_approved) {
            abort(403);
        }
        
        // Reject the travel order
        $travelOrder->update([
            'vp_approved' => false,
            'vp_approved_at' => now(),
            'status' => 'cancelled',
        ]);

        return redirect()->back()
            ->with('success', 'Travel order rejected successfully.');
    }
}