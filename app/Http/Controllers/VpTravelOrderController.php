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
            ->where(function ($query) {
                // For regular employees: head_approved must be true
                $query->whereHas('employee', function ($subQuery) {
                    $subQuery->where('is_head', 0)
                              ->where('is_divisionhead', 0)
                              ->orWhereNull('is_head')
                              ->orWhereNull('is_divisionhead');
                })->where('head_approved', true)
                // For heads: divisionhead_approved must be true
                ->orWhereHas('employee', function ($subQuery) {
                    $subQuery->where('is_head', 1)
                              ->where('is_divisionhead', 0)
                              ->orWhereNull('is_divisionhead');
                })->where('divisionhead_approved', true)
                // For division heads: direct approval (no prior approval needed)
                ->orWhereHas('employee', function ($subQuery) {
                    $subQuery->where('is_divisionhead', 1);
                });
            });
        
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
        
        $travelOrders = $query->orderByRaw('(CASE WHEN travel_orders.employee_id IN (SELECT id FROM employees WHERE is_head = 1) THEN divisionhead_approved_at ELSE head_approved_at END) DESC')->get();
        
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
        
        // Ensure the travel order has been approved by the appropriate authority
        // For regular employees: head_approved must be true
        // For heads: divisionhead_approved must be true
        // For division heads: no prior approval needed
        if ($travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            // This is a head's travel order, check division head approval
            if (!$travelOrder->divisionhead_approved) {
                abort(403);
            }
        } else if (!$travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            // This is a regular employee's travel order, check head approval
            if (!$travelOrder->head_approved) {
                abort(403);
            }
        }
        // For division heads, no prior approval check needed
        
        // Ensure the travel order hasn't already been approved by VP
        if ($travelOrder->vp_approved) {
            abort(403);
        }
        
        // Determine the status based on employee type
        // For division heads, status remains pending until president approval
        // For others, status becomes approved
        $status = 'pending';
        if (!$travelOrder->employee->is_divisionhead) {
            $status = 'approved';
        }
        
        // Approve the travel order
        $travelOrder->update([
            'vp_approved' => true,
            'vp_approved_at' => now(),
            'status' => $status,
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
        
        // Ensure the travel order has been approved by the appropriate authority
        // For regular employees: head_approved must be true
        // For heads: divisionhead_approved must be true
        // For division heads: no prior approval needed
        if ($travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            // This is a head's travel order, check division head approval
            if (!$travelOrder->divisionhead_approved) {
                abort(403);
            }
        } else if (!$travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            // This is a regular employee's travel order, check head approval
            if (!$travelOrder->head_approved) {
                abort(403);
            }
        }
        // For division heads, no prior approval check needed
        
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