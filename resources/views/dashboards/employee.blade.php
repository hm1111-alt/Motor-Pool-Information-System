@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Employee Dashboard') }}
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
                            <p class="text-gray-600 mt-1">Your personal dashboard for vehicle reservations and requests.</p>
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

            <!-- Action Cards -->
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
                                    <a href="{{ route('travel-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-green-300 text-green-800 rounded-lg hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm font-medium transition duration-200 shadow-sm">
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
                                    <a href="{{ route('travel-orders.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-indigo-300 text-indigo-800 rounded-lg hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-sm font-medium transition duration-200 shadow-sm">
                                        Create Request
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calendar -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-5 transition-all duration-200 hover:shadow-md">
                            <div class="flex items-start">
                                <div class="rounded-lg bg-blue-600 p-3 flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Schedule</h3>
                                    <p class="text-gray-600 text-sm mt-1 mb-3">View your upcoming vehicle assignments and scheduled trips.</p>
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
            
            <!-- Quick Overview -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
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
            
            <!-- My Trip Tickets Section -->
            @if(isset($myTripTickets) && $myTripTickets->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">My Trip Tickets</h2>
                        <a href="{{ route('employee.trip-tickets.index') }}" class="text-[#1e6031] hover:text-[#164f2a] font-medium text-sm">
                            View All →
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($myTripTickets as $ticket)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-900">Ticket #{{ $ticket->ticket_number }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <span class="font-medium">Destination:</span> {{ $ticket->itinerary->destination ?? 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <span class="font-medium">Date:</span> 
                                            {{ $ticket->itinerary->date_from?->format('M d, Y') ?? 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <span class="font-medium">Vehicle:</span> 
                                            {{ $ticket->itinerary->vehicle->make ?? 'N/A' }} {{ $ticket->itinerary->vehicle->model ?? '' }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <span class="font-medium">Driver:</span> 
                                            {{ $ticket->itinerary->driver->full_name ?? 'N/A' }}
                                        </p>
                                        @if($ticket->head_of_party && (str_contains($ticket->head_of_party, Auth::user()->employee->first_name) || str_contains($ticket->head_of_party, Auth::user()->employee->last_name)))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                                                Head of Party
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                                                Passenger
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $ticket->status }}
                                        </span>
                                        <div class="mt-2">
                                            <a href="{{ route('employee.trip-tickets.show', $ticket->id) }}" 
                                               class="text-sm text-[#1e6031] hover:text-[#164f2a] font-medium">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection