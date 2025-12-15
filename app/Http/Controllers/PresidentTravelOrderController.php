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
     * Display a listing of travel orders for president approval with tab support.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Build the query based on the selected tab
        $query = TravelOrder::whereHas('employee', function ($query) {
                $query->where('is_vp', 1); // Only VPs
            })
            ->where('head_approved', true)
            ->where('vp_approved', true)
            ->where('president_approved', true); // Already approved by VP
        
        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('destination', 'LIKE', "%{$search}%")
                  ->orWhere('purpose', 'LIKE', "%{$search}%")
                  ->orWhere('remarks', 'LIKE', "%{$search}%")
                  ->orWhereHas('employee', function($q) use ($search) {
                      $q->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        switch ($tab) {
            case 'approved':
                $query->where('status', 'approved');
                break;
            case 'cancelled':
                $query->where('status', 'cancelled');
                break;
            case 'pending':
            default:
                $query->where('status', 'pending');
                break;
        }
        
        // Check if this is an AJAX request for partial updates
        if ($request->ajax() || $request->get('ajax')) {
            $travelOrders = $query->orderBy('created_at', 'desc')->get();
            return view('travel-orders.approvals.partials.table-rows', compact('travelOrders', 'tab'))->render();
        }
        
        // Paginate results
        $travelOrders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('travel-orders.approvals.president-index', compact('travelOrders', 'tab', 'search'));
    }

    /**
     * Approve a travel order.
     */
    public function approve(TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Ensure the travel order is from a VP
        if (!$travelOrder->employee->is_vp) {
            abort(403);
        }
        
        // Ensure all prior approvals are in place
        if (!$travelOrder->head_approved || !$travelOrder->vp_approved || !$travelOrder->president_approved) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been finalized
        if ($travelOrder->status !== 'pending') {
            abort(403);
        }
        
        // Approve the travel order
        $travelOrder->update([
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
        
        // Ensure the travel order is from a VP
        if (!$travelOrder->employee->is_vp) {
            abort(403);
        }
        
        // Ensure all prior approvals are in place
        if (!$travelOrder->head_approved || !$travelOrder->vp_approved || !$travelOrder->president_approved) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been finalized
        if ($travelOrder->status !== 'pending') {
            abort(403);
        }
        
        // Reject the travel order
        $travelOrder->update([
            'status' => 'cancelled',
        ]);

        return redirect()->back()
            ->with('success', 'Travel order rejected successfully.');
    }
}