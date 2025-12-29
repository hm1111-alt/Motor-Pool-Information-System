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
        
        // Build the query based on the selected tab
        $query = TravelOrder::whereHas('employee', function ($query) use ($employee) {
                $query->where('office_id', $employee->office_id)
                      ->where('is_divisionhead', 1) // Only division heads
                      ->where(function ($query) {
                          $query->where('is_vp', 0)
                                ->orWhereNull('is_vp');
                      })
                      ->where(function ($query) {
                          $query->where('is_president', 0)
                                ->orWhereNull('is_president');
                      });
            })
            ->where('head_approved', true)
            ->where('vp_approved', true); // Already approved by division head
        
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
                $query->where('president_approved', true);
                break;
            case 'cancelled':
                $query->where('president_approved', false);
                break;
            case 'pending':
            default:
                $query->where('president_approved', null);
                break;
        }
        
        // Get paginated results
        $travelOrders = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->except('page'));
        
        // Check if this is an AJAX request for partial updates
        if ($request->ajax() || $request->get('ajax')) {
            return response()->json([
                'table_body' => view('travel-orders.approvals.partials.table-rows', compact('travelOrders', 'tab'))->render(),
                'pagination' => (string) $travelOrders->withQueryString()->links()
            ]);
        }
        
        return view('travel-orders.approvals.vp-index', compact('travelOrders', 'tab', 'search'));
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
        
        // Ensure the travel order is from a division head
        if (!$travelOrder->employee->is_divisionhead) {
            abort(403);
        }
        
        // Ensure the head and division head have already approved this travel order
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
            'status' => 'approved', // Fully approved
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
        
        // Ensure the travel order is from a division head
        if (!$travelOrder->employee->is_divisionhead) {
            abort(403);
        }
        
        // Ensure the head and division head have already approved this travel order
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