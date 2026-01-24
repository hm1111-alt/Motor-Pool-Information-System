<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;

class VpTravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders for VP approval with tab support.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Find the office_id of the current employee's primary position
        $primaryPosition = $employee->positions()->where('is_primary', true)->first();
        $officeId = $primaryPosition ? $primaryPosition->office_id : null;
        
        $query = TravelOrder::where(function ($query) {
                // Include division head travel orders that need VP approval (division heads approve their own)
                $query->whereHas('employee.officer', function ($officerQuery) {
                    $officerQuery->where('division_head', true)
                          ->where('vp', false)
                          ->where('president', false);
                })
                ->where(function($subQuery) {
                    // Division head travel orders don't need head approval, they go directly to VP
                    $subQuery->whereNull('vp_approved');
                })
                ->orWhere(function ($orQuery) {
                    // Include unit head travel orders that have been approved by division head
                    $orQuery->whereHas('employee.officer', function ($officerQuery) {
                        $officerQuery->where('unit_head', true)
                              ->where('division_head', false)
                              ->where('vp', false)
                              ->where('president', false);
                    })
                    ->where('divisionhead_approved', true);
                });
            })
            ->whereHas('position', function ($positionQuery) use ($officeId) {
                $positionQuery->where('office_id', $officeId);
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
        
        // Get paginated results with position information
        $travelOrders = $query->with('position', 'employee')->orderBy('created_at', 'desc')->paginate(10)->appends($request->except('page'));
        
        // Check if this is an AJAX request for partial updates
        if ($request->ajax() || $request->get('ajax')) {
            return response()->json([
                'table_body' => view('travel-orders.approvals.partials.table-rows', compact('travelOrders', 'tab'))->with('travelOrders', $travelOrders->load('position', 'employee'))->render(),
                'pagination' => (string) $travelOrders->withQueryString()->links()
            ]);
        }
        
        return view('travel-orders.approvals.vp-index', compact('travelOrders', 'tab', 'search'));
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
        
        // Check if the travel order belongs to an employee in the VP's office
        $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $vpOfficeId = $vpPrimaryPosition ? $vpPrimaryPosition->office_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderOfficeId = $travelOrderPosition->office_id;
        $travelOrderDivisionId = $travelOrderPosition->division_id;
        
        // For unit head travel orders, check if they're from the same division
        $isFromDivision = false;
        if ($travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
            $vpDivisionId = $vpPrimaryPosition ? $vpPrimaryPosition->division_id : null;
            $isFromDivision = $travelOrderDivisionId === $vpDivisionId;
        }
        
        // Allow if travel order is from employee in VP's office
        $isFromOffice = $travelOrderOfficeId === $vpOfficeId;
        
        // Also allow if it's the VP's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        if (!$isFromOffice && !$isFromDivision && !$isOwn) {
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
        
        // Ensure the VP can only approve travel orders from their office
        $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $vpOfficeId = $vpPrimaryPosition ? $vpPrimaryPosition->office_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderOfficeId = $travelOrderPosition->office_id;
        
        if ($travelOrderOfficeId !== $vpOfficeId) {
            abort(403);
        }
        
        // Ensure the travel order is from a division head or unit head
        if (!$travelOrder->employee || (!$travelOrder->employee->is_divisionhead && !$travelOrder->employee->is_head)) {
            abort(403);
        }
        
        // For unit head travel orders, ensure division head has approved
        // For division head travel orders, no head approval needed (they approve their own)
        if ($travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            // Unit head travel order - check division head approval
            if (!$travelOrder->divisionhead_approved) {
                abort(403);
            }
        } elseif ($travelOrder->employee->is_divisionhead) {
            // Division head travel order - no head approval needed, they go directly to VP
            // Just ensure this is the right stage (not yet approved by VP)
            if (!is_null($travelOrder->vp_approved)) {
                abort(403);
            }
        } else {
            // Regular employee travel order - check head approval
            if (!$travelOrder->head_approved) {
                abort(403);
            }
        }
        
        // Ensure the travel order hasn't already been approved by VP
        if (!is_null($travelOrder->vp_approved)) {
            abort(403);
        }
        
        // Ensure the travel order is still at the VP approval stage (not yet approved by president)
        if (!is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Approve the travel order
        $travelOrder->update([
            'vp_approved' => true,
            'vp_approved_at' => now(),
            'status' => $travelOrder->employee->is_divisionhead ? 'pending' : 'approved', // Division heads need president approval, others are approved
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
        $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $vpOfficeId = $vpPrimaryPosition ? $vpPrimaryPosition->office_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderOfficeId = $travelOrderPosition->office_id;
        $travelOrderDivisionId = $travelOrderPosition->division_id;
        
        // For unit head travel orders, check if they're from the same division
        $isFromDivision = false;
        if ($travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            $vpPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
            $vpDivisionId = $vpPrimaryPosition ? $vpPrimaryPosition->division_id : null;
            $isFromDivision = $travelOrderDivisionId === $vpDivisionId;
        }
        
        // Allow if travel order is from employee in VP's office
        $isFromOffice = $travelOrderOfficeId === $vpOfficeId;
        
        // Also allow if it's the VP's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        if ($travelOrderOfficeId !== $vpOfficeId && !$isFromDivision && !$isOwn) {
            abort(403);
        }
        
        // Ensure the travel order is from a division head or unit head
        if (!$travelOrder->employee || (!$travelOrder->employee->is_divisionhead && !$travelOrder->employee->is_head)) {
            abort(403);
        }
        
        // For unit head travel orders, ensure division head has approved
        // For division head travel orders, no head approval needed (they approve their own)
        if ($travelOrder->employee->is_head && !$travelOrder->employee->is_divisionhead) {
            // Unit head travel order - check division head approval
            if (!$travelOrder->divisionhead_approved) {
                abort(403);
            }
        } elseif ($travelOrder->employee->is_divisionhead) {
            // Division head travel order - no head approval needed, they go directly to VP
            // Just ensure this is the right stage (not yet approved by VP)
            if (!is_null($travelOrder->vp_approved)) {
                abort(403);
            }
        } else {
            // Regular employee travel order - check head approval
            if (!$travelOrder->head_approved) {
                abort(403);
            }
        }
        
        // Ensure the travel order hasn't already been approved by VP
        if (!is_null($travelOrder->vp_approved)) {
            abort(403);
        }
        
        // Ensure the travel order is still at the VP approval stage (not yet approved by president)
        if (!is_null($travelOrder->president_approved)) {
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