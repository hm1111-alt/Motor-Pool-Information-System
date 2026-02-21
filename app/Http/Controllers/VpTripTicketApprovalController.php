<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TripTicket;
use App\Models\Employee;

class VpTripTicketApprovalController extends Controller
{
    /**
     * Display pending trip tickets for VP approval in motorpool dashboard.
     */
    public function vpPending(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'pending'); // Default to pending tab
        
        // Check if user is authorized (VP, admin, or motorpool admin)
        if (!$user->isVp() && !$user->isAdmin() && !$user->isMotorpoolAdmin()) {
            abort(403, 'Unauthorized to view VP trip ticket approvals');
        }
        
        // Additional check: Only VPs from Office of the Vice President for Administration can access
        if ($user->isVp() && !$user->isAdmin() && !$user->isMotorpoolAdmin()) {
            $isVpOfAdministration = $user->employee->positions()->whereHas('office', function($query) {
                $query->where('office_name', 'Office of the Vice President for Administration');
            })->exists();
            
            if (!$isVpOfAdministration) {
                abort(403, 'Only VP of Office of the Vice President for Administration can approve trip tickets');
            }
        }
        
        // Base query
        $query = TripTicket::with(['itinerary.travelOrder.employee', 'itinerary.vehicle', 'itinerary.driver']);
            
        // Apply tab filtering
        switch ($tab) {
            case 'approved':
                $query->where('status', 'Approved');
                break;
            case 'cancelled':
                $query->where('status', 'Cancelled');
                break;
            case 'pending':
            default:
                $query->where('status', 'Pending');
                break;
        }
        
        $tripTickets = $query->get();
        
        // Get counts for each tab
        $pendingCount = TripTicket::where('status', 'Pending')->count();
        $approvedCount = TripTicket::where('status', 'Approved')->count();
        $cancelledCount = TripTicket::where('status', 'Cancelled')->count();
            
        return view('trip-tickets.approvals.vp-pending', compact('tripTickets', 'tab', 'pendingCount', 'approvedCount', 'cancelledCount'));
    }
    
    /**
     * Display a listing of trip tickets for VP approval with tab support.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Get the tab parameter, default to 'pending'
        $tab = $request->get('tab', 'pending');
        
        // Get search term if provided
        $search = $request->get('search', '');
        
        // Build query based on the selected tab
        $query = TripTicket::with(['itinerary.travelOrder.employee', 'itinerary.travelOrder.position']);
        
        // For VPs, show all pending trip tickets that need approval
        // No office-based filtering for trip tickets
        
        // Apply search filter if provided
        if ($search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->whereHas('itinerary.travelOrder.employee', function ($empQuery) use ($search) {
                    $empQuery->where('first_name', 'LIKE', "%{$search}%")
                             ->orWhere('last_name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('itinerary', function ($itineraryQuery) use ($search) {
                    $itineraryQuery->where('destination', 'LIKE', "%{$search}%")
                                   ->orWhere('purpose', 'LIKE', "%{$search}%");
                });
            });
        }
        
        switch ($tab) {
            case 'approved':
                // Trip tickets that have been approved by VP
                $query->whereIn('status', ['Approved', 'Completed']);
                break;
            case 'cancelled':
                // Cancelled trip tickets
                $query->whereIn('status', ['Cancelled']);
                break;
            case 'pending':
            default:
                // Pending trip tickets that need VP approval
                $query->where('status', 'Pending');
                break;
        }
        
        $tripTickets = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('trip-tickets.vp-approvals.index', compact('tripTickets', 'tab', 'search'));
    }
    
    /**
     * Approve a trip ticket (change status to Approved).
     */
    public function approve(TripTicket $tripTicket): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Ensure the user is a VP
        if (!$employee->is_vp) {
            abort(403);
        }
        
        // Additional check: Only VPs from Office of the Vice President for Administration can approve
        $isVpOfAdministration = $employee->positions()->whereHas('office', function($query) {
            $query->where('office_name', 'Office of the Vice President for Administration');
        })->exists();
        
        if (!$isVpOfAdministration) {
            abort(403, 'Only VP of Office of the Vice President for Administration can approve trip tickets');
        }
        
        // Only allow approving if status is currently Pending
        if ($tripTicket->status !== 'Pending') {
            abort(403);
        }
        
        // Update status to Approved
        $tripTicket->update([
            'status' => 'Approved',
        ]);
        
        return redirect()->back()
            ->with('success', 'Trip ticket approved successfully.');
    }
    
    /**
     * Reject a trip ticket (change status to Cancelled).
     */
    public function reject(TripTicket $tripTicket): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Ensure the user is a VP
        if (!$employee->is_vp) {
            abort(403);
        }
        
        // Additional check: Only VPs from Office of the Vice President for Administration can reject
        $isVpOfAdministration = $employee->positions()->whereHas('office', function($query) {
            $query->where('office_name', 'Office of the Vice President for Administration');
        })->exists();
        
        if (!$isVpOfAdministration) {
            abort(403, 'Only VP of Office of the Vice President for Administration can reject trip tickets');
        }
        
        // Only allow rejecting if status is currently Pending
        if ($tripTicket->status !== 'Pending') {
            abort(403);
        }
        
        // Update status to Cancelled
        $tripTicket->update([
            'status' => 'Cancelled',
        ]);
        
        return redirect()->back()
            ->with('success', 'Trip ticket rejected successfully.');
    }
    
    /**
     * Display the specified trip ticket for VP approval.
     */
    public function show(TripTicket $tripTicket): View
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Ensure the user is a VP
        if (!$employee->is_vp) {
            abort(403);
        }
        
        return view('trip-tickets.vp-approvals.show', compact('tripTicket'));
    }
}