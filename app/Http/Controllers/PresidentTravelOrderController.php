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
        
        // Build the query to include all travel orders that could be relevant to the president
        // This includes both VP and division head travel orders regardless of approval status
        $query = TravelOrder::where(function ($subQuery) {
                // Include VP travel orders (they go directly to president)
                $subQuery->whereHas('employee.officer', function ($officerQuery) {
                    $officerQuery->where('vp', true);
                });
                
                // Or include division head travel orders that need president approval
                $subQuery->orWhere(function ($orSubQuery) {
                    $orSubQuery->whereHas('employee.officer', function ($officerQuery) {
                        $officerQuery->where('division_head', true)
                              ->where('vp', false)
                              ->where('president', false);
                    })
                    ->where('vp_approved', true); // Already approved by VP, need president approval
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
                $query->where(function($q) {
                    $q->where('president_approved', true);
                });
                break;
            case 'cancelled':
                $query->where(function($q) {
                    $q->where('president_approved', false);
                });
                break;
            case 'pending':
            default:
                $query->where(function($q) {
                    $q->where('president_approved', null);
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
        // This includes VP travel orders and division head travel orders that need president approval
        $requiresPresApproval = false;
        
        if ($travelOrder->employee && $travelOrder->employee->is_vp && is_null($travelOrder->president_approved)) {
            $requiresPresApproval = true;
        } elseif ($travelOrder->employee && $travelOrder->employee->is_divisionhead && $travelOrder->vp_approved && is_null($travelOrder->president_approved)) {
            $requiresPresApproval = true;
        }
        
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
        
        // Ensure the travel order is from a VP or division head
        $isValidTravelOrder = false;
        
        // Check if it's a VP travel order
        if ($travelOrder->employee && $travelOrder->employee->is_vp && is_null($travelOrder->president_approved)) {
            $isValidTravelOrder = true;
        } 
        // Check if it's a division head travel order
        elseif ($travelOrder->employee && $travelOrder->employee->is_divisionhead && $travelOrder->vp_approved && is_null($travelOrder->president_approved)) {
            $isValidTravelOrder = true;
        }
        
        if (!$isValidTravelOrder) {
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
        
        // Ensure the travel order is from a VP or division head
        $isValidTravelOrder = false;
        
        // Check if it's a VP travel order
        if ($travelOrder->employee && $travelOrder->employee->is_vp && is_null($travelOrder->president_approved)) {
            $isValidTravelOrder = true;
        } 
        // Check if it's a division head travel order
        elseif ($travelOrder->employee && $travelOrder->employee->is_divisionhead && $travelOrder->vp_approved && is_null($travelOrder->president_approved)) {
            $isValidTravelOrder = true;
        }
        
        if (!$isValidTravelOrder) {
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