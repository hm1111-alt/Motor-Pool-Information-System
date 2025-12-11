<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;

class DivisionHeadTravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders for division head approval.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Build the query for heads' travel orders within this division
        $query = TravelOrder::whereHas('employee', function ($query) use ($employee) {
                $query->where('division_id', $employee->division_id)
                      ->where('is_head', 1); // Only heads' travel orders
            });
        
        // Apply filters based on tab
        switch ($tab) {
            case 'approved':
                $query->where('divisionhead_approved', true);
                break;
            case 'cancelled':
                $query->where('divisionhead_approved', false);
                break;
            case 'pending':
            default:
                $query->where('divisionhead_approved', null);
                break;
        }
        
        $travelOrders = $query->orderBy('created_at', 'desc')->get();
        
        return view('travel-orders.approvals.divisionhead-index', compact('travelOrders', 'tab'));
    }

    /**
     * Approve a travel order.
     */
    public function approve(TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Ensure the division head can only approve travel orders from their division
        if ($travelOrder->employee->division_id !== $employee->division_id) {
            abort(403);
        }
        
        // Ensure the travel order is from a head
        if (!$travelOrder->employee->is_head) {
            abort(403);
        }
        
        // Ensure the division head cannot approve their own travel order
        if ($travelOrder->employee_id === $employee->id) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved or rejected
        if (!is_null($travelOrder->divisionhead_approved)) {
            abort(403);
        }
        
        // Approve the travel order
        $travelOrder->update([
            'divisionhead_approved' => true,
            'divisionhead_approved_at' => now(),
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
        
        // Ensure the division head can only reject travel orders from their division
        if ($travelOrder->employee->division_id !== $employee->division_id) {
            abort(403);
        }
        
        // Ensure the travel order is from a head
        if (!$travelOrder->employee->is_head) {
            abort(403);
        }
        
        // Ensure the division head cannot reject their own travel order
        if ($travelOrder->employee_id === $employee->id) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved or rejected
        if (!is_null($travelOrder->divisionhead_approved)) {
            abort(403);
        }
        
        // Reject the travel order
        $travelOrder->update([
            'divisionhead_approved' => false,
            'divisionhead_approved_at' => now(),
            'status' => 'cancelled',
        ]);

        return redirect()->back()
            ->with('success', 'Travel order rejected successfully.');
    }
}