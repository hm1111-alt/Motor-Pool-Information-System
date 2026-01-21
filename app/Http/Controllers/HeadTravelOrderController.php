<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;

class HeadTravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders for head approval with tab support.
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
        // Find the unit_id of the current employee's primary position
        $primaryPosition = $employee->positions()->where('is_primary', true)->first();
        $unitId = $primaryPosition ? $primaryPosition->unit_id : null;
        
        $query = TravelOrder::whereHas('employee', function ($query) {
                $query->whereDoesntHave('officer', function ($officerQuery) {
                    $officerQuery->where('unit_head', true);
                })
                ->whereDoesntHave('officer', function ($officerQuery) {
                    $officerQuery->where('division_head', true);
                })
                ->whereDoesntHave('officer', function ($officerQuery) {
                    $officerQuery->where('vp', true);
                })
                ->whereDoesntHave('officer', function ($officerQuery) {
                    $officerQuery->where('president', true);
                });
            })
            ->whereHas('position', function ($positionQuery) use ($unitId) {
                $positionQuery->where('unit_id', $unitId);
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
        
        // Get paginated results with position information
        $travelOrders = $query->with('position', 'employee')->orderBy('created_at', 'desc')->paginate(10)->appends($request->except('page'));
        
        // Check if this is an AJAX request for partial updates
        if ($request->ajax() || $request->get('ajax')) {
            return response()->json([
                'table_body' => view('travel-orders.approvals.partials.table-rows', compact('travelOrders', 'tab'))->with('travelOrders', $travelOrders->load('position', 'employee'))->render(),
                'pagination' => (string) $travelOrders->withQueryString()->links()
            ]);
        }
        
        return view('travel-orders.approvals.head-index', compact('travelOrders', 'tab', 'search'));
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
        
        // Check if the travel order belongs to an employee in the head's unit
        $headPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $headUnitId = $headPrimaryPosition ? $headPrimaryPosition->unit_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderUnitId = $travelOrderPosition->unit_id;
        
        // Allow if travel order is from employee in head's unit
        $isFromUnit = $travelOrderUnitId === $headUnitId;
        
        // Also allow if it's the head's own travel order (though they shouldn't typically approve their own)
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        if (!$isFromUnit && !$isOwn) {
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
        
        // Ensure the head can only approve travel orders from their unit
        $headPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $headUnitId = $headPrimaryPosition ? $headPrimaryPosition->unit_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderUnitId = $travelOrderPosition->unit_id;
        
        if ($travelOrderUnitId !== $headUnitId) {
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
        
        // Ensure the travel order is still at the head approval stage (not yet approved by higher authorities)
        // If division head or VP or President has already approved, this is not the right stage
        if (!is_null($travelOrder->divisionhead_approved) || 
            !is_null($travelOrder->vp_approved) || 
            !is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Approve the travel order
        $travelOrder->update([
            'head_approved' => true,
            'head_approved_at' => now(),
            'status' => 'pending', // Still pending division head approval
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
        $headPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $headUnitId = $headPrimaryPosition ? $headPrimaryPosition->unit_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderUnitId = $travelOrderPosition->unit_id;
        
        if ($travelOrderUnitId !== $headUnitId) {
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
        
        // Ensure the travel order is still at the head approval stage (not yet approved by higher authorities)
        // If division head or VP or President has already approved, this is not the right stage
        if (!is_null($travelOrder->divisionhead_approved) || 
            !is_null($travelOrder->vp_approved) || 
            !is_null($travelOrder->president_approved)) {
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