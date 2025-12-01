<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;
use App\Models\Employee;

class TravelOrderController extends Controller
{
    /**
     * Display a listing of the travel orders with tabs for pending, approved, and cancelled.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employeeId = $user->employee->id ?? null;
        
        // Check if this is for approvals - if status parameter is present and user is head/divisionhead/vp
        if ($request->has('status') && 
            $user->employee && 
            ($user->employee->is_head || $user->employee->is_divisionhead || $user->employee->is_vp)) {
            return $this->approvals($request);
        }
        
        // Get the active tab from the request, default to 'pending'
        // Check both 'tab' and 'status' parameters for compatibility
        $activeTab = $request->get('tab', $request->get('status', 'pending'));
        
        // Get search query
        $search = $request->get('search');
        
        // Build the query
        $query = TravelOrder::where('employee_id', $employeeId);
        
        // Apply search filter if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('destination', 'like', '%' . $search . '%')
                  ->orWhere('purpose', 'like', '%' . $search . '%')
                  ->orWhere('date_from', 'like', '%' . $search . '%')
                  ->orWhere('date_to', 'like', '%' . $search . '%')
                  ->orWhere('departure_time', 'like', '%' . $search . '%');
            });
        }
        
        // Apply status filter based on the active tab
        switch ($activeTab) {
            case 'pending':
                $query->where(function ($q) {
                    $q->where(function ($q) {
                        $q->whereNull('divisionhead_approved')
                          ->whereNull('vp_approved');
                    })->orWhere(function ($q) {
                        $q->where('divisionhead_approved', 1)
                          ->whereNull('vp_approved');
                    })->orWhere(function ($q) {
                        $q->where('divisionhead_approved', 1)
                          ->where('vp_approved', 0);
                    });
                });
                break;
                
            case 'approved':
                $query->where('divisionhead_approved', 1)
                      ->where('vp_approved', 1);
                break;
                
            case 'cancelled':
                $query->where(function ($q) {
                    $q->where(function ($q) {
                        $q->where('divisionhead_approved', 0)
                          ->where('vp_approved', 0);
                    })->orWhere(function ($q) {
                        $q->where('divisionhead_approved', 0)
                          ->whereNull('vp_approved');
                    });
                });
                break;
        }
        
        // Paginate the results (10 per page)
        $travelOrders = $query->orderBy('created_at', 'desc')->paginate(10)->appends(['tab' => $activeTab, 'search' => $search]);
        
        return view('travel-orders.index', compact('travelOrders', 'activeTab', 'search'));
    }
    
    /**
     * Display travel orders requiring approval for heads
     */
    public function approvals(Request $request): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Log the employee information for debugging
        \Log::info('Getting approvals for employee:', [
            'employee_id' => $employee->id,
            'is_head' => $employee->is_head,
            'is_divisionhead' => $employee->is_divisionhead,
            'is_vp' => $employee->is_vp,
            'unit_id' => $employee->unit_id,
            'division_id' => $employee->division_id
        ]);
        
        // Get the active tab from the request, default to 'pending'
        $activeTab = $request->get('status', 'pending');
        
        // Get search query
        $search = $request->get('search');
        
        // Build the query for approvals
        $query = TravelOrder::with('employee');
        
        // If employee is a division head, show orders from their division
        if ($employee->is_divisionhead && $employee->division_id) {
            $query->whereHas('employee', function ($q) use ($employee) {
                $q->where('division_id', $employee->division_id);
            });
            
            // Apply status filter based on the active tab
            switch ($activeTab) {
                case 'pending':
                    $query->where('divisionhead_approved', 0)->whereNull('divisionhead_declined');
                    break;
                    
                case 'approved':
                    $query->where('divisionhead_approved', 1);
                    break;
                    
                case 'cancelled':
                    $query->where('divisionhead_declined', 1);
                    break;
            }
        }
        // If employee is a VP, show orders pending VP approval
        elseif ($employee->is_vp) {
            // Log VP information for debugging
            \Log::info('VP user check:', [
                'user_id' => $user->id,
                'employee_id' => $employee->id,
                'is_vp' => $employee->is_vp
            ]);
            
            // Apply status filter based on the active tab
            switch ($activeTab) {
                case 'pending':
                    \Log::info('VP query conditions:', [
                        'divisionhead_approved' => 1,
                        'vp_approved' => null,
                        'vp_declined' => null
                    ]);
                    $query->where('divisionhead_approved', 1)->whereNull('vp_approved')->whereNull('vp_declined');
                    // Log the query SQL for debugging
                    \Log::info('VP pending query SQL:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);
                    break;
                    
                case 'approved':
                    $query->where('vp_approved', 1);
                    break;
                    
                case 'cancelled':
                    $query->where('vp_declined', 1);
                    break;
            }
        }
        // If employee is a unit head, show orders from employees in their unit
        elseif ($employee->is_head && $employee->unit_id) {
            $query->whereHas('employee', function ($q) use ($employee) {
                $q->where('unit_id', $employee->unit_id);
            });
            
            // Apply status filter based on the active tab
            switch ($activeTab) {
                case 'pending':
                    $query->whereNull('head_approved')->whereNull('head_disapproved');
                    break;
                    
                case 'approved':
                    // For unit heads, show orders that have been approved by the head
                    $query->where('head_approved', 1);
                    break;
                    
                case 'cancelled':
                    // For unit heads, show orders that have been cancelled by the head
                    $query->where('head_disapproved', 1);
                    break;
            }
        }
        // If employee is a president, show orders pending president approval
        elseif ($employee->is_president) {
            // Apply status filter based on the active tab
            switch ($activeTab) {
                case 'pending':
                    $query->where('vp_approved', 1)->whereNull('president_approved')->whereNull('president_declined');
                    break;
                    
                case 'approved':
                    $query->where('president_approved', 1);
                    break;
                    
                case 'cancelled':
                    $query->where('president_declined', 1);
                    break;
            }
        }
        
        // Apply search filter if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('destination', 'like', '%' . $search . '%')
                  ->orWhere('purpose', 'like', '%' . $search . '%')
                  ->orWhereHas('employee', function ($q) use ($search) {
                      $q->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Paginate the results (10 per page)
        $travelOrders = $query->orderBy('created_at', 'desc')->paginate(10)->appends(['status' => $activeTab, 'search' => $search]);
        
        // Log the results for debugging
        \Log::info('Found travel orders for approval:', [
            'count' => $travelOrders->count(), 
            'orders' => $travelOrders->pluck('id'),
            'active_tab' => $activeTab
        ]);
        
        // Log the actual travel order data for debugging
        foreach ($travelOrders as $order) {
            \Log::info('Travel order details:', [
                'id' => $order->id,
                'employee_id' => $order->employee_id,
                'head_approved' => $order->head_approved,
                'divisionhead_approved' => $order->divisionhead_approved,
                'vp_approved' => $order->vp_approved,
                'vp_declined' => $order->vp_declined
            ]);
        }
        
        return view('travel-orders.approvals', compact('travelOrders', 'search'));
    }

    /**
     * Show the form for creating a new travel order.
     */
    public function create(): View
    {
        return view('travel-orders.create');
    }

    /**
     * Store a newly created travel order in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Store method called');
        \Log::info('Request headers: ' . json_encode($request->headers->all()));
        \Log::info('Is AJAX: ' . ($request->ajax() ? 'true' : 'false'));
        
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'purpose' => 'required|string|max:255',
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'destination' => 'required|string|max:255',
                'departure_time' => 'required|date_format:H:i',
            ]);

            // Get the authenticated user
            $user = Auth::user();
            $employee = $user->employee;

            // Create the travel order
            $travelOrder = new TravelOrder();
            $travelOrder->employee_id = $employee->id ?? null;
            $travelOrder->purpose = $validatedData['purpose'];
            $travelOrder->date_from = $validatedData['date_from'];
            $travelOrder->date_to = $validatedData['date_to'];
            $travelOrder->destination = $validatedData['destination'];
            $travelOrder->departure_time = $validatedData['departure_time'];
            
            // Set initial status based on employee type according to the approval workflow
            if ($employee->is_president) {
                // President's travel orders go directly to Motorpool Admin
                $travelOrder->head_approved = true;
                $travelOrder->head_approved_at = now();
                $travelOrder->divisionhead_approved = true;
                $travelOrder->divisionhead_approved_at = now();
                $travelOrder->vp_approved = true;
                $travelOrder->vp_approved_at = now();
                $travelOrder->status = 'Pending';
            } elseif ($employee->is_vp) {
                // VP's travel orders need President approval
                $travelOrder->head_approved = true;
                $travelOrder->head_approved_at = now();
                $travelOrder->divisionhead_approved = true;
                $travelOrder->divisionhead_approved_at = now();
                $travelOrder->status = 'Pending';
            } elseif ($employee->is_divisionhead) {
                // Division Head's travel orders need VP and President approval
                $travelOrder->head_approved = true;
                $travelOrder->head_approved_at = now();
                $travelOrder->status = 'Pending';
            } elseif ($employee->is_head) {
                // Head's travel orders need Division Head and VP approval
                $travelOrder->status = 'Pending';
            } else {
                // Regular employees only need Head and VP approval (skip division head and president)
                $travelOrder->status = 'Pending';
            }
            
            $travelOrder->save();

            \Log::info('Travel order created successfully');
            \Log::info('Is AJAX: ' . ($request->ajax() ? 'true' : 'false'));
            
            // Check if this is an AJAX request
            if ($request->ajax()) {
                \Log::info('AJAX request detected');
                return response()->json([
                    'success' => true,
                    'message' => 'Travel order created successfully!',
                    'redirect' => route('travel-orders.index', ['tab' => 'pending'])
                ]);
            }

            // Redirect to the travel orders index page with success message in session
            return redirect()->route('travel-orders.index', ['tab' => 'pending'])->with('success', 'Travel order created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation exception: ' . $e->getMessage());
            // Handle validation errors for AJAX requests
            if ($request->ajax()) {
                $errors = $e->validator->errors()->all();
                return response()->json([
                    'success' => false,
                    'message' => implode('\n', $errors)
                ], 422);
            }

            // Re-throw for non-AJAX requests
            throw $e;
        } catch (\Exception $e) {
            \Log::error('General exception: ' . $e->getMessage());
            // Check if this is an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the travel order. Please try again.'
                ], 500);
            }

            // Redirect back with error message
            return redirect()->back()->with('error', 'An error occurred while creating the travel order. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified travel order.
     */
    public function edit(TravelOrder $travelOrder): View
    {
        // Ensure the travel order belongs to the authenticated user
        $user = Auth::user();
        if ($travelOrder->employee_id !== $user->employee->id) {
            abort(403);
        }

        return view('travel-orders.edit', compact('travelOrder'));
    }

    /**
     * Update the specified travel order in storage.
     */
    public function update(Request $request, TravelOrder $travelOrder): RedirectResponse
    {
        // Ensure the travel order belongs to the authenticated user
        $user = Auth::user();
        if ($travelOrder->employee_id !== $user->employee->id) {
            abort(403);
        }

        // Validate the request data
        $validatedData = $request->validate([
            'purpose' => 'required|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'destination' => 'required|string|max:255',
            'departure_time' => 'required|date_format:H:i',
        ]);

        // Update the travel order
        $travelOrder->purpose = $validatedData['purpose'];
        $travelOrder->date_from = $validatedData['date_from'];
        $travelOrder->date_to = $validatedData['date_to'];
        $travelOrder->destination = $validatedData['destination'];
        $travelOrder->departure_time = $validatedData['departure_time'];
        $travelOrder->save();

        // Redirect to the travel orders index page with success message in session
        return redirect()->route('travel-orders.index', ['tab' => 'pending'])->with('success', 'Travel order updated successfully!');
    }

    /**
     * Remove the specified travel order from storage.
     */
    public function destroy(Request $request, TravelOrder $travelOrder)
    {
        try {
            // Ensure the travel order belongs to the authenticated user
            $user = Auth::user();
            if ($travelOrder->employee_id !== $user->employee->id) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access.'
                    ], 403);
                }
                abort(403);
            }

            // Delete the travel order
            $travelOrder->delete();

            // Check if this is an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Travel order deleted successfully!',
                    'redirect' => route('travel-orders.index', ['tab' => 'pending'])
                ]);
            }

            // Redirect back with a success message in session
            return redirect()->route('travel-orders.index', ['tab' => 'pending'])->with('success', 'Travel order deleted successfully!');
        } catch (\Exception $e) {
            // Check if this is an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the travel order. Please try again.'
                ], 500);
            }

            // Redirect back with error message
            return redirect()->back()->with('error', 'An error occurred while deleting the travel order. Please try again.');
        }
    }
    
    /**
     * Display the specified travel order.
     */
    public function show(TravelOrder $travelOrder): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Check if user can view this travel order
        $canView = false;
        
        // User can view their own travel orders
        if ($travelOrder->employee_id === $employee->id) {
            $canView = true;
        }
        // Division heads can view orders from their division
        elseif ($employee->is_divisionhead && $employee->division_id && 
                $travelOrder->employee->division_id === $employee->division_id) {
            $canView = true;
        }
        // VPs can view orders that are pending their approval
        elseif ($employee->is_vp && $travelOrder->divisionhead_approved && 
                is_null($travelOrder->vp_approved) && is_null($travelOrder->vp_declined)) {
            $canView = true;
        }
        // Unit heads can view orders from their unit
        elseif ($employee->is_head && $employee->unit_id && 
                $travelOrder->employee->unit_id === $employee->unit_id) {
            $canView = true;
        }
        // Presidents can view orders that are pending their approval
        elseif ($employee->is_president && $travelOrder->vp_approved && 
                is_null($travelOrder->president_approved) && is_null($travelOrder->president_declined)) {
            $canView = true;
        }
        
        if (!$canView) {
            abort(403);
        }
        
        return view('travel-orders.show', compact('travelOrder'));
    }
    
    /**
     * Approve or decline a travel order
     */
    public function approve(Request $request, TravelOrder $travelOrder)
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Validate the request
        $request->validate([
            'approval_type' => 'required|in:divisionhead,vp,head,president',
            'action' => 'required|in:approve,decline'
        ]);
        
        // Check if user can approve this travel order
        $canApprove = false;
        
        if ($request->approval_type === 'divisionhead') {
            // Division heads can approve orders from their division
            if ($employee->is_divisionhead && $employee->division_id && 
                $travelOrder->employee->division_id === $employee->division_id &&
                !$travelOrder->divisionhead_approved && !$travelOrder->divisionhead_declined) {
                $canApprove = true;
            }
            // Unit heads can approve orders from their unit (as division heads)
            // But for regular employees, division head approval is auto-set
            elseif ($employee->is_head && $employee->unit_id && 
                    $travelOrder->employee->unit_id === $employee->unit_id &&
                    !$travelOrder->divisionhead_approved && !$travelOrder->divisionhead_declined) {
                // Check if this is a regular employee (not head, divisionhead, vp, or president)
                $isRegularEmployee = !$travelOrder->employee->is_head && 
                                   !$travelOrder->employee->is_divisionhead && 
                                   !$travelOrder->employee->is_vp && 
                                   !$travelOrder->employee->is_president;
                
                // For regular employees, they don't need division head approval
                // For heads, they do need division head approval
                if (!$isRegularEmployee) {
                    $canApprove = true;
                }
            }
        } elseif ($request->approval_type === 'vp') {
            // VPs can approve orders that have been approved by division heads
            if ($employee->is_vp && $travelOrder->divisionhead_approved && 
                is_null($travelOrder->vp_approved) && is_null($travelOrder->vp_declined)) {
                $canApprove = true;
            }
        } elseif ($request->approval_type === 'head') {
            // Unit heads can approve orders from their unit using the new head approval fields
            if ($employee->is_head && $employee->unit_id && 
                $travelOrder->employee->unit_id === $employee->unit_id &&
                !$travelOrder->head_approved && !$travelOrder->head_disapproved) {
                $canApprove = true;
            }
        } elseif ($request->approval_type === 'president') {
            // Presidents can approve orders that have been approved by VPs
            if ($employee->is_president && $travelOrder->vp_approved && 
                is_null($travelOrder->president_approved) && is_null($travelOrder->president_declined)) {
                $canApprove = true;
            }
        }
        
        if (!$canApprove) {
            return redirect()->back()->with('error', 'You are not authorized to approve this travel order.');
        }
        
        // Process the approval/decline based on the multi-level workflow
        if ($request->approval_type === 'divisionhead') {
            if ($request->action === 'approve') {
                $travelOrder->divisionhead_approved = true;
                $travelOrder->divisionhead_approved_at = now();
                $travelOrder->divisionhead_approved_by = $employee->id;
                
                // Determine next step based on employee role
                if ($travelOrder->employee->is_head) {
                    // Head's travel order goes to VP after division head approval
                    $travelOrder->status = 'Pending';
                } elseif ($travelOrder->employee->is_divisionhead) {
                    // Division head's travel order goes to President after division head approval
                    $travelOrder->status = 'Pending';
                } else {
                    // Regular employee's travel order goes to VP after division head approval
                    $travelOrder->status = 'Pending';
                }
            } else {
                $travelOrder->divisionhead_declined = true;
                $travelOrder->divisionhead_declined_at = now();
                $travelOrder->divisionhead_declined_by = $employee->id;
                // If division head declines, the overall status becomes Cancelled
                $travelOrder->status = 'Cancelled';
            }
        } elseif ($request->approval_type === 'vp') {
            if ($request->action === 'approve') {
                $travelOrder->vp_approved = true;
                $travelOrder->vp_approved_at = now();
                $travelOrder->vp_approved_by = $employee->id;
                
                // Determine next step based on employee role
                if ($travelOrder->employee->is_divisionhead) {
                    // Division head's travel order goes to President after VP approval
                    $travelOrder->status = 'Pending';
                } elseif ($travelOrder->employee->is_vp) {
                    // VP's travel order goes to President after VP approval
                    $travelOrder->status = 'Pending';
                } else {
                    // For regular employees and heads, travel order is approved after VP approval
                    $travelOrder->status = 'Approved';
                }
            } else {
                $travelOrder->vp_declined = true;
                $travelOrder->vp_declined_at = now();
                $travelOrder->vp_declined_by = $employee->id;
                
                // If VP declines, the overall status becomes Cancelled
                $travelOrder->status = 'Cancelled';
            }
        } elseif ($request->approval_type === 'president') {
            if ($request->action === 'approve') {
                $travelOrder->president_approved = true;
                $travelOrder->president_approved_at = now();
                $travelOrder->president_approved_by = $employee->id;
                
                // President's approval sends it to Motorpool Admin
                if ($travelOrder->employee->is_president) {
                    $travelOrder->status = 'Pending';
                } else {
                    // VP's and Division Head's travel orders are approved after President approval
                    $travelOrder->status = 'Approved';
                }
            } else {
                $travelOrder->president_declined = true;
                $travelOrder->president_declined_at = now();
                $travelOrder->president_declined_by = $employee->id;
                
                // If President declines, the overall status becomes Cancelled
                $travelOrder->status = 'Cancelled';
            }
        } elseif ($request->approval_type === 'head') {
            if ($request->action === 'approve') {
                $travelOrder->head_approved = true;
                $travelOrder->head_approved_at = now();
                $travelOrder->head_approved_by = $employee->id;
                
                // For regular employees, skip division head and go directly to VP
                $isRegularEmployee = !$travelOrder->employee->is_head && 
                                   !$travelOrder->employee->is_divisionhead && 
                                   !$travelOrder->employee->is_vp && 
                                   !$travelOrder->employee->is_president;
                
                \Log::info('Employee role check:', [
                    'employee_id' => $travelOrder->employee->id,
                    'is_head' => $travelOrder->employee->is_head,
                    'is_divisionhead' => $travelOrder->employee->is_divisionhead,
                    'is_vp' => $travelOrder->employee->is_vp,
                    'is_president' => $travelOrder->employee->is_president,
                    'is_regular_employee' => $isRegularEmployee
                ]);
                
                if ($isRegularEmployee) {
                    // Log for debugging
                    \Log::info('Regular employee approval:', [
                        'employee_id' => $travelOrder->employee->id,
                        'is_head' => $travelOrder->employee->is_head,
                        'is_divisionhead' => $travelOrder->employee->is_divisionhead,
                        'is_vp' => $travelOrder->employee->is_vp,
                        'is_president' => $travelOrder->employee->is_president
                    ]);
                    // For regular employees, we only need Head and VP approval
                    // Set divisionhead_approved to true to skip division head approval
                    $travelOrder->divisionhead_approved = true;
                    $travelOrder->divisionhead_approved_at = now();
                    $travelOrder->divisionhead_approved_by = $employee->id; // Use head's ID for division head approval
                    \Log::info('Setting divisionhead_approved for regular employee:', [
                        'travel_order_id' => $travelOrder->id,
                        'divisionhead_approved' => $travelOrder->divisionhead_approved
                    ]);
                    $travelOrder->status = 'Pending';
                } else {
                    // For other roles, follow normal workflow
                    $travelOrder->status = 'Pending';
                }
            } else {
                $travelOrder->head_disapproved = true;
                $travelOrder->head_disapproved_at = now();
                $travelOrder->head_disapproved_by = $employee->id;
                // If head disapproves, the overall status becomes Cancelled
                $travelOrder->status = 'Cancelled';
            }
        }
        
        $travelOrder->save();
        
        // Check if the user is a head/divisionhead/vp approving orders, redirect back to approvals page
        if ($employee->is_head || $employee->is_divisionhead || $employee->is_vp) {
            // For heads, division heads, and VPs, redirect back to the approvals page with the same status
            $status = $request->get('status', 'pending');
            return redirect()->route('travel-orders.index', ['status' => $status])->with('success', 'Travel order ' . $request->action . 'd successfully!');
        } else {
            // For regular employees, redirect to travel requests page
            $tab = $request->get('tab', 'pending');
            return redirect()->route('travel-orders.index', ['tab' => $tab])->with('success', 'Travel order ' . $request->action . 'd successfully!');
        }
    }
}