@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('VP Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Welcome, {{ Auth::user()->employee->first_name ?? Auth::user()->name }}!</h1>
                            <p class="text-gray-600 mt-1">VP dashboard for travel request management and institutional approvals.</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <div class="flex items-center">
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- My Travel Requests -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 p-5 transition-all duration-200 hover:shadow-md">
                            <div class="flex items-start">
                                <div class="rounded-lg bg-[#1e6031] p-3 flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">My Travel Requests</h3>
                                    <p class="text-gray-600 text-sm mt-1 mb-3">Manage your travel requests and view their status.</p>
                                    <a href="{{ route('vp.travel-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-green-300 text-green-800 rounded-lg hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm font-medium transition duration-200 shadow-sm">
                                        View Requests
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Create Travel Order -->
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl border border-indigo-200 p-5 transition-all duration-200 hover:shadow-md">
                            <div class="flex items-start">
                                <div class="rounded-lg bg-indigo-600 p-3 flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Create Travel Order</h3>
                                    <p class="text-gray-600 text-sm mt-1 mb-3">Submit a new travel request for approval.</p>
                                    <a href="{{ route('vp.travel-orders.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-indigo-300 text-indigo-800 rounded-lg hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-sm font-medium transition duration-200 shadow-sm">
                                        Create Request
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Institutional Calendar -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-5 transition-all duration-200 hover:shadow-md">
                            <div class="flex items-start">
                                <div class="rounded-lg bg-blue-600 p-3 flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Institution Schedule</h3>
                                    <p class="text-gray-600 text-sm mt-1 mb-3">View vehicle assignments and schedules across the institution.</p>
                                    <a href="{{ route('vehicle-calendar.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-blue-300 text-blue-800 rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm font-medium transition duration-200 shadow-sm">
                                        Open Calendar
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Approval Section (for all VPs) -->
            @if(auth()->user()->employee && auth()->user()->employee->is_vp)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Pending Approvals</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Travel Order Approvals (for all VPs) -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-5 transition-all duration-200 hover:shadow-md">
                            <div class="flex items-start">
                                <div class="rounded-lg bg-blue-600 p-3 flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Travel Order Approvals</h3>
                                    <p class="text-gray-600 text-sm mt-1 mb-3">Review and approve travel orders from your employees.</p>
                                    <div class="flex items-center mb-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $pendingTravelOrders ?? 0 }} Pending
                                        </span>
                                    </div>
                                    <a href="{{ route('travel-orders.approvals.vp') }}" class="inline-flex items-center px-4 py-2 bg-white border border-blue-300 text-blue-800 rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm font-medium transition duration-200 shadow-sm">
                                        Review Travel Orders
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Itinerary Approvals (only for VP of Office of the Vice President for Administration) -->
                        @if(isset($isVpOfAdministration) && $isVpOfAdministration)
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 p-5 transition-all duration-200 hover:shadow-md">
                            <div class="flex items-start">
                                <div class="rounded-lg bg-purple-600 p-3 flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Itinerary Approvals</h3>
                                    <p class="text-gray-600 text-sm mt-1 mb-3">Review and approve pending itineraries from unit heads.</p>
                                    <div class="flex items-center mb-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $pendingItineraries ?? 0 }} Pending
                                        </span>
                                    </div>
                                    <a href="{{ route('motorpool.approvals.itineraries.vp') }}" class="inline-flex items-center px-4 py-2 bg-white border border-purple-300 text-purple-800 rounded-lg hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 text-sm font-medium transition duration-200 shadow-sm">
                                        Review Itineraries
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Trip Ticket Approvals (only for VP of Office of the Vice President for Administration) -->
                        @if(isset($isVpOfAdministration) && $isVpOfAdministration)
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200 p-5 transition-all duration-200 hover:shadow-md">
                            <div class="flex items-start">
                                <div class="rounded-lg bg-orange-600 p-3 flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Trip Ticket Approvals</h3>
                                    <p class="text-gray-600 text-sm mt-1 mb-3">Review and approve pending trip tickets.</p>
                                    <div class="flex items-center mb-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $pendingTripTickets ?? 0 }} Pending
                                        </span>
                                    </div>
                                    <a href="{{ route('motorpool.approvals.triptickets.vp') }}" class="inline-flex items-center px-4 py-2 bg-white border border-orange-300 text-orange-800 rounded-lg hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 text-sm font-medium transition duration-200 shadow-sm">
                                        Review Trip Tickets
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Overview</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Total Requests -->
                        @php
                            $totalRequests = Auth::user()->employee->travelOrders->count();
                            $pendingRequests = Auth::user()->employee->travelOrders()->where('status', 'pending')->count();
                            $approvedRequests = Auth::user()->employee->travelOrders()->where('status', 'approved')->count();
                        @endphp
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-5">
                            <div class="flex items-center">
                                <div class="rounded-lg bg-blue-600 p-3 flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Total Requests</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalRequests }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pending Requests -->
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200 p-5">
                            <div class="flex items-center">
                                <div class="rounded-lg bg-yellow-500 p-3 flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Pending</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $pendingRequests }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Approved Requests -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 p-5">
                            <div class="flex items-center">
                                <div class="rounded-lg bg-green-600 p-3 flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Approved</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $approvedRequests }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection