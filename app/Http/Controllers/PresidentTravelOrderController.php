<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;

class PresidentTravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders for president approval with tab support.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Build the query based on the selected tab
        $query = TravelOrder::whereHas('employee', function ($query) {
                $query->whereHas('officer', function ($officerQuery) {
                    $officerQuery->where('vp', true);
                });
            })
            ->where('head_approved', true)
            ->where('vp_approved', true); // Already approved by head and VP
        
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
                $query->where(function($q) {
                    $q->where('status', 'pending_president_approval')
                      ->orWhere('status', 'pending');
                });
                break;
        }
        
        // Get paginated results with position information
        $travelOrders = $query->with('position', 'employee')->orderBy('created_at', 'desc')->paginate(10)->appends($request->except('page'));
        
        // Check if this is an AJAX request for partial updates
        if ($request->ajax() || $request->get('ajax')) {
            return response()->json([
                'table_body' => view('travel-orders.approvals.partials.table-rows', compact('travelOrders', 'tab'))->with('travelOrders', $travelOrders->load('position', 'employee'))->render(),
                'pagination' => (string) $travelOrders->withQueryString()->links()
            ]);
        }
        
        return view('travel-orders.approvals.president-index', compact('travelOrders', 'tab', 'search'));
    }

    /**
     * Display the specified resource for approval.
     */
    public function show(TravelOrder $travelOrder): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if employee exists
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an employee record. Please contact your administrator to set up your employee profile.');
        }
        
        // Check if the user is a president
        if (!$employee->is_president) {
            abort(403);
        }
        
        // Also allow if it's the president's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        // For presidents, allow viewing any travel order that requires presidential approval
        // This includes VP travel orders that need president approval
        $travelOrderPosition = $travelOrder->position;
        $requiresPresApproval = $travelOrderPosition && $travelOrderPosition->is_vp;
        
        if (!$requiresPresApproval && !$isOwn) {
            abort(403);
        }
        
        return view('travel-orders.show', compact('travelOrder'));
    }
    
    /**
     * Approve a travel order.
     */
    public function approve(TravelOrder $travelOrder): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Ensure the travel order is from a VP
        // Check if the position used for this travel order has VP role
        $travelOrderPosition = $travelOrder->position;
        if (!$travelOrderPosition || !$travelOrderPosition->is_vp) {
            abort(403);
        }
        
        // Ensure all prior approvals are in place
        if (!$travelOrder->head_approved || !$travelOrder->vp_approved) {
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
        
        // Ensure the travel order is from a VP
        // Check if the position used for this travel order has VP role
        $travelOrderPosition = $travelOrder->position;
        if (!$travelOrderPosition || !$travelOrderPosition->is_vp) {
            abort(403);
        }
        
        // Ensure all prior approvals are in place
        if (!$travelOrder->head_approved || !$travelOrder->vp_approved) {
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