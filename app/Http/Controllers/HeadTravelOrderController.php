<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;

class HeadTravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders for head approval with tab support.
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
        $query = TravelOrder::whereHas('employee', function ($query) use ($employee) {
                $query->where('unit_id', $employee->unit_id)
                      ->where(function ($query) {
                          $query->where('is_head', 0)
                                ->orWhereNull('is_head');
                      })
                      ->where(function ($query) {
                          $query->where('is_divisionhead', 0)
                                ->orWhereNull('is_divisionhead');
                      })
                      ->where(function ($query) {
                          $query->where('is_vp', 0)
                                ->orWhereNull('is_vp');
                      })
                      ->where(function ($query) {
                          $query->where('is_president', 0)
                                ->orWhereNull('is_president');
                      });
            });
        
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
                $query->where('head_approved', true);
                break;
            case 'cancelled':
                $query->where('head_approved', false);
                break;
            case 'pending':
            default:
                $query->where('head_approved', null);
                break;
        }
        
        // Check if this is an AJAX request for partial updates
        if ($request->ajax() || $request->get('ajax')) {
            $travelOrders = $query->orderBy('created_at', 'desc')->get();
            return view('travel-orders.approvals.partials.table-rows', compact('travelOrders', 'tab'))->render();
        }
        
        // Paginate results
        $travelOrders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('travel-orders.approvals.head-index', compact('travelOrders', 'tab', 'search'));
    }

    /**
     * Approve a travel order.
     */
    public function approve(TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Ensure the head can only approve travel orders from their unit
        if ($travelOrder->employee->unit_id !== $employee->unit_id) {
            abort(403);
        }
        
        // Ensure the head cannot approve their own travel order
        if ($travelOrder->employee_id === $employee->id) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved
        if ($travelOrder->head_approved) {
            abort(403);
        }
        
        // Approve the travel order
        $travelOrder->update([
            'head_approved' => true,
            'head_approved_at' => now(),
            'status' => 'pending', // Still pending VP approval
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
        
        // Ensure the head can only reject travel orders from their unit
        if ($travelOrder->employee->unit_id !== $employee->unit_id) {
            abort(403);
        }
        
        // Ensure the head cannot reject their own travel order
        if ($travelOrder->employee_id === $employee->id) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved
        if ($travelOrder->head_approved) {
            abort(403);
        }
        
        // Reject the travel order
        $travelOrder->update([
            'head_approved' => false,
            'head_approved_at' => now(),
            'status' => 'cancelled',
        ]);

        return redirect()->back()
            ->with('success', 'Travel order rejected successfully.');
    }
}