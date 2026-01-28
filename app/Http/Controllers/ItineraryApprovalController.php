<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Itinerary;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItineraryApprovalController extends Controller
{
    /**
     * Show itineraries for unit head with tab filtering
     */
    public function unitHeadPending(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'pending'); // Default to pending tab
        
        // Base query
        $query = Itinerary::with(['travelOrder.employee', 'driver', 'vehicle']);
            
        // If user is not a motorpool admin, limit to itineraries related to their unit
        if (!$user->isMotorpoolAdmin() && !$user->isAdmin()) {
            if ($user->employee && $user->employee->unit && str_contains(strtolower($user->employee->unit->unit_name), 'transportation services')) {
                // Transportation services unit head can see all itineraries (as per business logic)
                // Or if you want to restrict to only their unit's itineraries, uncomment the next line
                // $query->whereHas('travelOrder.employee', function($q) use ($user) {
                //     $q->where('unit_id', $user->employee->unit->id);
                // });
            } else {
                // Other unit heads should only see itineraries related to their unit
                $query->whereHas('travelOrder.employee', function($q) use ($user) {
                    if ($user->employee && $user->employee->unit) {
                        $q->where('unit_id', $user->employee->unit->id);
                    }
                });
            }
        }
        
        // Apply tab filtering
        switch ($tab) {
            case 'approved':
                $query->where('unit_head_approved', true)
                      ->whereNotNull('unit_head_approved_at');
                break;
            case 'cancelled':
                $query->where('unit_head_approved', false)
                      ->whereNotNull('unit_head_approved_at');
                break;
            case 'pending':
            default:
                $query->where('unit_head_approved', false)
                      ->whereNull('unit_head_approved_at');
                break;
        }
        
        $itineraries = $query->get();
        
        // Get counts for each tab
        $pendingCount = Itinerary::where('unit_head_approved', false)
            ->whereNull('unit_head_approved_at')
            ->when(!$user->isMotorpoolAdmin() && !$user->isAdmin(), function($q) use ($user) {
                if ($user->employee && $user->employee->unit && str_contains(strtolower($user->employee->unit->unit_name), 'transportation services')) {
                    return $q;
                } else {
                    return $q->whereHas('travelOrder.employee', function($q2) use ($user) {
                        if ($user->employee && $user->employee->unit) {
                            $q2->where('unit_id', $user->employee->unit->id);
                        }
                    });
                }
            })
            ->count();
            
        $approvedCount = Itinerary::where('unit_head_approved', true)
            ->whereNotNull('unit_head_approved_at')
            ->when(!$user->isMotorpoolAdmin() && !$user->isAdmin(), function($q) use ($user) {
                if ($user->employee && $user->employee->unit && str_contains(strtolower($user->employee->unit->unit_name), 'transportation services')) {
                    return $q;
                } else {
                    return $q->whereHas('travelOrder.employee', function($q2) use ($user) {
                        if ($user->employee && $user->employee->unit) {
                            $q2->where('unit_id', $user->employee->unit->id);
                        }
                    });
                }
            })
            ->count();
            
        $cancelledCount = Itinerary::where('unit_head_approved', false)
            ->whereNotNull('unit_head_approved_at')
            ->when(!$user->isMotorpoolAdmin() && !$user->isAdmin(), function($q) use ($user) {
                if ($user->employee && $user->employee->unit && str_contains(strtolower($user->employee->unit->unit_name), 'transportation services')) {
                    return $q;
                } else {
                    return $q->whereHas('travelOrder.employee', function($q2) use ($user) {
                        if ($user->employee && $user->employee->unit) {
                            $q2->where('unit_id', $user->employee->unit->id);
                        }
                    });
                }
            })
            ->count();
            
        return view('itineraries.approvals.unit-head-pending', compact('itineraries', 'tab', 'pendingCount', 'approvedCount', 'cancelledCount'));
    }
    
    /**
     * Approve itinerary by unit head
     */
    public function approveByUnitHead($id): RedirectResponse
    {
        $itinerary = Itinerary::findOrFail($id);
        
        // Check if user has unit head role
        $user = Auth::user();
        if (!$user->isUnitHead() && !$user->isAdmin() && !$user->isMotorpoolAdmin()) {
            abort(403, 'Unauthorized to approve itineraries');
        }
        
        // Additional check: if not admin/motorpool admin, verify user can approve this itinerary
        if (!$user->isAdmin() && !$user->isMotorpoolAdmin()) {
            // Allow transportation services unit head to approve any itinerary
            // or restrict to their own unit
            if (!($user->employee && $user->employee->unit && str_contains(strtolower($user->employee->unit->unit_name), 'transportation services'))) {
                // For other unit heads, check if itinerary belongs to their unit
                if ($itinerary->travelOrder && $itinerary->travelOrder->employee && $itinerary->travelOrder->employee->unit_id !== $user->employee->unit->id) {
                    abort(403, 'Unauthorized to approve this itinerary');
                }
            }
        }
        
        $itinerary->update([
            'unit_head_approved' => true,
            'unit_head_approved_by' => $user->id,
            'unit_head_approved_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Itinerary approved by Unit Head successfully.');
    }
    
    /**
     * Reject itinerary by unit head
     */
    public function rejectByUnitHead($id): RedirectResponse
    {
        $itinerary = Itinerary::findOrFail($id);
        
        // Check if user has unit head role
        $user = Auth::user();
        if (!$user->isUnitHead() && !$user->isAdmin() && !$user->isMotorpoolAdmin()) {
            abort(403, 'Unauthorized to reject itineraries');
        }
        
        // Additional check: if not admin/motorpool admin, verify user can reject this itinerary
        if (!$user->isAdmin() && !$user->isMotorpoolAdmin()) {
            // Allow transportation services unit head to reject any itinerary
            // or restrict to their own unit
            if (!($user->employee && $user->employee->unit && str_contains(strtolower($user->employee->unit->unit_name), 'transportation services'))) {
                // For other unit heads, check if itinerary belongs to their unit
                if ($itinerary->travelOrder && $itinerary->travelOrder->employee && $itinerary->travelOrder->employee->unit_id !== $user->employee->unit->id) {
                    abort(403, 'Unauthorized to reject this itinerary');
                }
            }
        }
        
        $itinerary->update([
            'unit_head_approved' => false,
            'unit_head_approved_by' => $user->id,
            'unit_head_approved_at' => now(),
        ]);
        
        return redirect()->back()->with('info', 'Itinerary rejected by Unit Head.');
    }
    
    /**
     * Show itineraries for VP with tab filtering
     */
    public function vpPending(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'pending'); // Default to pending tab
        
        // Base query
        $query = Itinerary::where('unit_head_approved', true)
            ->with(['travelOrder.employee', 'driver', 'vehicle']);
            
        // If user is not a motorpool admin, VP, or admin, limit access
        if (!$user->isMotorpoolAdmin() && !$user->isAdmin() && !$user->isVp()) {
            abort(403, 'Unauthorized to view VP pending itineraries');
        }
        
        // TEMPORARY: Show all unit head approved itineraries to VP
        // TODO: Determine correct office-based filtering logic
        /*
        // If user is a VP, only show itineraries related to their office/division
        if ($user->isVp() && !$user->isAdmin() && !$user->isMotorpoolAdmin()) {
            // Find the office that the transportation services unit belongs to
            // First, find the transportation services unit
            $transportationUnit = Unit::whereRaw('LOWER(unit_name) LIKE ?', ['%transportation services%'])->first();
            
            if ($transportationUnit) {
                // Get the division of the transportation services unit
                $transportationDivision = $transportationUnit->division;
                
                if ($transportationDivision) {
                    // Get the office of the transportation services unit
                    $transportationOffice = $transportationDivision->office;
                    
                    if ($transportationOffice) {
                        // Only show itineraries where the employee belongs to a unit in the same office
                        $query->whereHas('travelOrder.employee', function($q) use ($transportationOffice) {
                            $q->whereHas('positions', function($q2) use ($transportationOffice) {
                                $q2->whereHas('unit.division', function($q3) use ($transportationOffice) {
                                    $q3->where('office_id', $transportationOffice->id);
                                });
                            });
                        });
                    }
                }
            }
        }
        */
        
        // Apply tab filtering
        switch ($tab) {
            case 'approved':
                $query->where('vp_approved', true)
                      ->whereNotNull('vp_approved_at');
                break;
            case 'cancelled':
                $query->where('vp_approved', false)
                      ->whereNotNull('vp_approved_at');
                break;
            case 'pending':
            default:
                $query->where('vp_approved', false)
                      ->whereNull('vp_approved_at');
                break;
        }
        
        $itineraries = $query->get();
        
        // Get counts for each tab
        $pendingCount = Itinerary::where('unit_head_approved', true)
            ->where('vp_approved', false)
            ->whereNull('vp_approved_at')
            ->count();
            
        $approvedCount = Itinerary::where('unit_head_approved', true)
            ->where('vp_approved', true)
            ->whereNotNull('vp_approved_at')
            ->count();
            
        $cancelledCount = Itinerary::where('unit_head_approved', true)
            ->where('vp_approved', false)
            ->whereNotNull('vp_approved_at')
            ->count();
            
        return view('itineraries.approvals.vp-pending', compact('itineraries', 'tab', 'pendingCount', 'approvedCount', 'cancelledCount'));
    }
    
    /**
     * Approve itinerary by VP
     */
    public function approveByVp($id): RedirectResponse
    {
        $itinerary = Itinerary::findOrFail($id);
        
        // Check if user has VP role
        $user = Auth::user();
        if (!$user->isVp() && !$user->isAdmin() && !$user->isMotorpoolAdmin()) {
            abort(403, 'Unauthorized to approve itineraries');
        }
        
        // Additional check: if not admin/motorpool admin, verify user can approve this itinerary
        if (!$user->isAdmin() && !$user->isMotorpoolAdmin() && $user->isVp()) {
            // TEMPORARY: Disable office-based authorization check
            // TODO: Determine correct office-based filtering logic
            /*
            // Find the office that the transportation services unit belongs to
            // First, find the transportation services unit
            $transportationUnit = Unit::whereRaw('LOWER(unit_name) LIKE ?', ['%transportation services%'])->first();
            
            if ($transportationUnit) {
                // Get the division of the transportation services unit
                $transportationDivision = $transportationUnit->division;
                
                if ($transportationDivision) {
                    // Get the office of the transportation services unit
                    $transportationOffice = $transportationDivision->office;
                    
                    if ($transportationOffice) {
                        // Verify that this itinerary belongs to the same office
                        $itineraryHasCorrectOffice = $itinerary->travelOrder->employee->positions()
                            ->whereHas('unit.division', function($q) use ($transportationOffice) {
                                $q->where('office_id', $transportationOffice->id);
                            })
                            ->exists();
                            
                        if (!$itineraryHasCorrectOffice) {
                            abort(403, 'Unauthorized to approve this itinerary - not in your office jurisdiction');
                        }
                    }
                }
            }
            */
        }
        
        $itinerary->update([
            'vp_approved' => true,
            'vp_approved_by' => $user->id,
            'vp_approved_at' => now(),
            'status' => 'Approved' // Final approval sets the status to Approved
        ]);
        
        return redirect()->back()->with('success', 'Itinerary approved by VP successfully. Itinerary is now fully approved.');
    }
    
    /**
     * Reject itinerary by VP
     */
    public function rejectByVp($id): RedirectResponse
    {
        $itinerary = Itinerary::findOrFail($id);
        
        // Check if user has VP role
        $user = Auth::user();
        if (!$user->isVp() && !$user->isAdmin() && !$user->isMotorpoolAdmin()) {
            abort(403, 'Unauthorized to reject itineraries');
        }
        
        // Additional check: if not admin/motorpool admin, verify user can reject this itinerary
        if (!$user->isAdmin() && !$user->isMotorpoolAdmin() && $user->isVp()) {
            // TEMPORARY: Disable office-based authorization check
            // TODO: Determine correct office-based filtering logic
            /*
            // Find the office that the transportation services unit belongs to
            // First, find the transportation services unit
            $transportationUnit = Unit::whereRaw('LOWER(unit_name) LIKE ?', ['%transportation services%'])->first();
            
            if ($transportationUnit) {
                // Get the division of the transportation services unit
                $transportationDivision = $transportationUnit->division;
                
                if ($transportationDivision) {
                    // Get the office of the transportation services unit
                    $transportationOffice = $transportationDivision->office;
                    
                    if ($transportationOffice) {
                        // Verify that this itinerary belongs to the same office
                        $itineraryHasCorrectOffice = $itinerary->travelOrder->employee->positions()
                            ->whereHas('unit.division', function($q) use ($transportationOffice) {
                                $q->where('office_id', $transportationOffice->id);
                            })
                            ->exists();
                            
                        if (!$itineraryHasCorrectOffice) {
                            abort(403, 'Unauthorized to reject this itinerary - not in your office jurisdiction');
                        }
                    }
                }
            }
            */
        }
        
        $itinerary->update([
            'vp_approved' => false,
            'vp_approved_by' => $user->id,
            'vp_approved_at' => now(),
            'status' => 'Rejected' // Rejection sets the status to Rejected
        ]);
        
        return redirect()->back()->with('info', 'Itinerary rejected by VP.');
    }
    
    /**
     * Show approved itineraries
     */
    public function approved()
    {
        $user = Auth::user();
        
        $query = Itinerary::where('vp_approved', true)
            ->whereNotNull('vp_approved_at')
            ->with(['travelOrder.employee', 'driver', 'vehicle']);
            
        // If user is not a motorpool admin, admin, or has approval authority, limit access
        if (!$user->isMotorpoolAdmin() && !$user->isAdmin() && !$user->isVp() && !$user->isUnitHead()) {
            abort(403, 'Unauthorized to view approved itineraries');
        }
        
        // TEMPORARY: Show all approved itineraries to VP
        // TODO: Determine correct office-based filtering logic
        /*
        // If user is a VP, only show itineraries related to their office/division
        if ($user->isVp() && !$user->isAdmin() && !$user->isMotorpoolAdmin()) {
            // Find the office that the transportation services unit belongs to
            // First, find the transportation services unit
            $transportationUnit = Unit::whereRaw('LOWER(unit_name) LIKE ?', ['%transportation services%'])->first();
            
            if ($transportationUnit) {
                // Get the division of the transportation services unit
                $transportationDivision = $transportationUnit->division;
                
                if ($transportationDivision) {
                    // Get the office of the transportation services unit
                    $transportationOffice = $transportationDivision->office;
                    
                    if ($transportationOffice) {
                        // Only show itineraries where the employee belongs to a unit in the same office
                        $query->whereHas('travelOrder.employee', function($q) use ($transportationOffice) {
                            $q->whereHas('positions', function($q2) use ($transportationOffice) {
                                $q2->whereHas('unit.division', function($q3) use ($transportationOffice) {
                                    $q3->where('office_id', $transportationOffice->id);
                                });
                            });
                        });
                    }
                }
            }
        }
        */
        
        $itineraries = $query->get();
            
        return view('itineraries.approvals.approved', compact('itineraries'));
    }
}
