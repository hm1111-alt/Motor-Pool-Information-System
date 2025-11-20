@extends('layouts.employee')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Head Dashboard
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
                    <!-- Welcome Section -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-2">
                            Welcome, {{ Auth::user()->employee->first_name ?? Auth::user()->name }}!
                        </h3>
                        <p class="text-gray-600">Here's what you can do today</p>
                    </div>
                    
                    <!-- Main Content Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- My Requests -->
                        <a href="{{ route('travel-orders.index') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                            <div class="p-6">
                                <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">My Requests</h4>
                                <p class="text-gray-600 text-sm">View and manage your travel requests</p>
                            </div>
                        </a>
                        
                        <!-- Create Travel Order -->
                        <a href="{{ route('travel-orders.create') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                            <div class="p-6">
                                <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">Create Request</h4>
                                <p class="text-gray-600 text-sm">Submit a new travel order</p>
                            </div>
                        </a>
                        
                        <!-- Approve Travel Orders -->
                        <a href="{{ route('travel-orders.index', ['status' => 'pending']) }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                            <div class="p-6">
                                <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">Approve Requests</h4>
                                <p class="text-gray-600 text-sm">Review and approve travel orders</p>
                            </div>
                        </a>
                        
                        <!-- Calendar -->
                        <a href="{{ route('vehicle-calendar.index') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                            <div class="p-6">
                                <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">Calendar</h4>
                                <p class="text-gray-600 text-sm">View travel schedule only</p>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Quick Stats Section -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Quick Overview</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600">Pending Requests</p>
                                <p class="text-2xl font-bold text-[#1e6031]">{{ $pendingCount ?? 0 }}</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600">Approved Requests</p>
                                <p class="text-2xl font-bold text-[#1e6031]">{{ $approvedCount ?? 0 }}</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600">Total Requests</p>
                                <p class="text-2xl font-bold text-[#1e6031]">{{ $totalCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection