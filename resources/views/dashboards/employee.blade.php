@extends('layouts.employee')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Employee Dashboard
        </h2>
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
                        
                        <!-- Vehicle Calendar -->
                        <a href="{{ route('vehicle-calendar.index') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                            <div class="p-6">
                                <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">Vehicle Calendar</h4>
                                <p class="text-gray-600 text-sm">Check vehicle availability</p>
                            </div>
                        </a>
                        
                        <!-- Travel History -->
                        <a href="#" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                            <div class="p-6">
                                <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">Travel History</h4>
                                <p class="text-gray-600 text-sm">View your past travel records</p>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Quick Stats Section -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Quick Overview</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600">Pending Requests</p>
                                <p class="text-2xl font-bold text-[#1e6031]">0</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600">Approved Requests</p>
                                <p class="text-2xl font-bold text-[#1e6031]">0</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600">Total Requests</p>
                                <p class="text-2xl font-bold text-[#1e6031]">0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection