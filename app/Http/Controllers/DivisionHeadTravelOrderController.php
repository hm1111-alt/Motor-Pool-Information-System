<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;

class DivisionHeadTravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders for division head approval with tab support.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Find the division_id of the current employee's primary position
        $primaryPosition = $employee->positions()->where('is_primary', true)->first();
        $divisionId = $primaryPosition ? $primaryPosition->division_id : null;
        
        $query = TravelOrder::whereHas('employee', function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereDoesntHave('officer') // Regular employees without officer records
                          ->orWhereHas('officer', function ($officerQuery) {
                              $officerQuery->where(function ($innerQuery) {
                                  $innerQuery->where('unit_head', true)  // Unit heads
                                        ->where('division_head', false)
                                        ->where('vp', false)
                                        ->where('president', false);
                              })
                              ->orWhere(function ($innerQuery) {
                                  $innerQuery->where('unit_head', false)  // Regular employees with officer records but no leadership roles
                                        ->where('division_head', false)
                                        ->where('vp', false)
                                        ->where('president', false);
                              });
                          });
                });
            })
            ->whereHas('position', function ($positionQuery) use ($divisionId) {
                $positionQuery->where('division_id', $divisionId);
            })
            ->where('head_approved', true);  // Already approved by head
        
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
        
        // Apply tab-specific filtering
        switch ($tab) {
            case 'approved':
                $query->where('divisionhead_approved', true);  // Approved by division head
                break;
            case 'cancelled':
                $query->where('divisionhead_approved', false);
                break;
            case 'pending':
            default:
                $query->whereNull('divisionhead_approved');  // Not yet approved by division head
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
        
        return view('travel-orders.approvals.divisionhead-index', compact('travelOrders', 'tab', 'search'));
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
        
        // Check if the travel order belongs to an employee in the division head's division
        $divisionHeadPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $divisionHeadDivisionId = $divisionHeadPrimaryPosition ? $divisionHeadPrimaryPosition->division_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderDivisionId = $travelOrderPosition->division_id;
        
        // Allow if travel order is from employee in division head's division
        $isFromDivision = $travelOrderDivisionId === $divisionHeadDivisionId;
        
        // Also allow if it's the division head's own travel order
        $isOwn = $travelOrder->employee_id === $employee->id;
        
        if (!$isFromDivision && !$isOwn) {
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
        
        // Ensure the user is a division head
        if (!$employee->is_divisionhead) {
            abort(403);
        }
        
        // Ensure the division head can only approve travel orders from their division
        $divisionHeadPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $divisionHeadDivisionId = $divisionHeadPrimaryPosition ? $divisionHeadPrimaryPosition->division_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderDivisionId = $travelOrderPosition->division_id;
        
        if ($travelOrderDivisionId !== $divisionHeadDivisionId) {
            abort(403);
        }
        
        // Ensure the travel order is from a regular employee or unit head (but not division head, VP, or president)
        $travelOrderPosition = $travelOrder->position;
        if ($travelOrderPosition && ($travelOrderPosition->is_division_head || $travelOrderPosition->is_vp || $travelOrderPosition->is_president)) {
            abort(403);
        }
        
        // Ensure the head has already approved this travel order
        if (!$travelOrder->head_approved) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved by division head
        if (!is_null($travelOrder->divisionhead_approved)) {
            abort(403);
        }
        
        // Ensure the travel order is still at the division head approval stage (not yet approved by higher authorities)
        // If VP or President has already approved, this is not the right stage
        if (!is_null($travelOrder->vp_approved) || !is_null($travelOrder->president_approved)) {
            abort(403);
        }
        
        // Approve the travel order
        $travelOrder->update([
            'divisionhead_approved' => true,
            'divisionhead_approved_at' => now(),
            'status' => 'approved', // Fully approved by division head for regular employee
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
        
        // Ensure the user is a division head
        if (!$employee->is_divisionhead) {
            abort(403);
        }
        
        // Ensure the division head can only reject travel orders from their division
        $divisionHeadPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
        $divisionHeadDivisionId = $divisionHeadPrimaryPosition ? $divisionHeadPrimaryPosition->division_id : null;
        
        $travelOrderPosition = $travelOrder->position;
        
        // If no position is assigned to the travel order, deny access
        if (!$travelOrderPosition) {
            abort(403);
        }
        
        $travelOrderDivisionId = $travelOrderPosition->division_id;
        
        if ($travelOrderDivisionId !== $divisionHeadDivisionId) {
            abort(403);
        }
        
        // Ensure the travel order is from a regular employee or unit head (but not division head, VP, or president)
        $travelOrderPosition = $travelOrder->position;
        if ($travelOrderPosition && ($travelOrderPosition->is_division_head || $travelOrderPosition->is_vp || $travelOrderPosition->is_president)) {
            abort(403);
        }
        
        // Ensure the head has already approved this travel order
        if (!$travelOrder->head_approved) {
            abort(403);
        }
        
        // Ensure the travel order hasn't already been approved by division head
        if (!is_null($travelOrder->divisionhead_approved)) {
            abort(403);
        }
        
        // Ensure the travel order is still at the division head approval stage (not yet approved by higher authorities)
        // If VP or President has already approved, this is not the right stage
        if (!is_null($travelOrder->vp_approved) || !is_null($travelOrder->president_approved)) {
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