@extends('layouts.employee')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Travel Order Details
        </h2>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ url()->previous() }}" class="text-[#1e6031] hover:text-[#164f2a] flex items-center">
                            <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back
                        </a>
                    </div>
                    
                    <!-- Travel Order Details -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Travel Order #{{ $travelOrder->id }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Employee Information -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Employee Information</h4>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">Name</p>
                                        <p class="font-medium">{{ $travelOrder->employee->first_name }} {{ $travelOrder->employee->last_name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Position</p>
                                        <p class="font-medium">{{ $travelOrder->employee->position_name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Office</p>
                                        <p class="font-medium">{{ $travelOrder->employee->office->office_name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Division</p>
                                        <p class="font-medium">{{ $travelOrder->employee->division->division_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Travel Details -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Travel Details</h4>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">Purpose</p>
                                        <p class="font-medium">{{ $travelOrder->purpose }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Destination</p>
                                        <p class="font-medium">{{ $travelOrder->destination }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Date Range</p>
                                        <p class="font-medium">
                                            {{ \Carbon\Carbon::parse($travelOrder->date_from)->format('F j, Y') }} - 
                                            {{ \Carbon\Carbon::parse($travelOrder->date_to)->format('F j, Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Departure Time</p>
                                        <p class="font-medium">{{ \Carbon\Carbon::parse($travelOrder->departure_time)->format('g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Information -->
                        <div class="mt-6 bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Approval Status</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="bg-white p-4 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-500">Head Approval</p>
                                    @if($travelOrder->head_approved)
                                        <p class="font-medium text-green-600">Approved</p>
                                        <p class="text-xs text-gray-500">{{ $travelOrder->head_approved_at ? $travelOrder->head_approved_at->format('M j, Y g:i A') : '' }}</p>
                                    @elseif($travelOrder->head_disapproved)
                                        <p class="font-medium text-red-600">Disapproved</p>
                                        <p class="text-xs text-gray-500">{{ $travelOrder->head_disapproved_at ? $travelOrder->head_disapproved_at->format('M j, Y g:i A') : '' }}</p>
                                    @else
                                        <p class="font-medium text-yellow-600">Pending</p>
                                    @endif
                                </div>
                                
                                <div class="bg-white p-4 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-500">Division Head Approval</p>
                                    @if($travelOrder->divisionhead_approved)
                                        <p class="font-medium text-green-600">Approved</p>
                                        <p class="text-xs text-gray-500">{{ $travelOrder->divisionhead_approved_at ? $travelOrder->divisionhead_approved_at->format('M j, Y g:i A') : '' }}</p>
                                    @elseif($travelOrder->divisionhead_declined)
                                        <p class="font-medium text-red-600">Declined</p>
                                        <p class="text-xs text-gray-500">{{ $travelOrder->divisionhead_declined_at ? $travelOrder->divisionhead_declined_at->format('M j, Y g:i A') : '' }}</p>
                                    @else
                                        <p class="font-medium text-yellow-600">Pending</p>
                                    @endif
                                </div>
                                
                                <div class="bg-white p-4 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-500">VP Approval</p>
                                    @if($travelOrder->vp_approved)
                                        <p class="font-medium text-green-600">Approved</p>
                                        <p class="text-xs text-gray-500">{{ $travelOrder->vp_approved_at ? $travelOrder->vp_approved_at->format('M j, Y g:i A') : '' }}</p>
                                    @elseif($travelOrder->vp_declined)
                                        <p class="font-medium text-red-600">Declined</p>
                                        <p class="text-xs text-gray-500">{{ $travelOrder->vp_declined_at ? $travelOrder->vp_declined_at->format('M j, Y g:i A') : '' }}</p>
                                    @else
                                        <p class="font-medium text-yellow-600">Pending</p>
                                    @endif
                                </div>
                                
                                <div class="bg-white p-4 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-500">Overall Status</p>
                                    <p class="font-medium 
                                        @if($travelOrder->status === 'Approved') text-green-600 
                                        @elseif($travelOrder->status === 'Cancelled') text-red-600 
                                        @else text-yellow-600 @endif">
                                        {{ $travelOrder->status }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons (for approvers) -->
                        @php
                            $user = Auth::user();
                            $employee = $user->employee;
                            $canApprove = false;
                            $approvalType = '';
                            
                            // Check if user can approve this travel order
                            if ($employee->is_divisionhead && $employee->division_id && 
                                $travelOrder->employee->division_id === $employee->division_id &&
                                !$travelOrder->divisionhead_approved && !$travelOrder->divisionhead_declined) {
                                $canApprove = true;
                                $approvalType = 'divisionhead';
                            } elseif ($employee->is_vp && $travelOrder->divisionhead_approved && 
                                      is_null($travelOrder->vp_approved) && is_null($travelOrder->vp_declined)) {
                                $canApprove = true;
                                $approvalType = 'vp';
                            } elseif ($employee->is_head && $employee->unit_id && 
                                      $travelOrder->employee->unit_id === $employee->unit_id &&
                                      !$travelOrder->head_approved && !$travelOrder->head_disapproved) {
                                $canApprove = true;
                                $approvalType = 'head';
                            }
                        @endphp
                        
                        @if($canApprove)
                            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Approve Travel Order</h4>
                                <p class="text-gray-600 mb-4">As a {{ ucfirst(str_replace(['divisionhead', 'vp', 'head'], ['Division Head', 'VP', 'Head'], $approvalType)) }}, you can approve or decline this travel order.</p>
                                
                                <form action="{{ route('travel-orders.approve', $travelOrder) }}" method="POST" class="flex space-x-4">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="approval_type" value="{{ $approvalType }}">
                                    
                                    <button type="submit" name="action" value="approve" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                        Approve
                                    </button>
                                    
                                    <button type="submit" name="action" value="decline" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                        Decline
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection