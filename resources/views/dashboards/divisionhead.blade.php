@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-lg text-gray-800 leading-tight">
        {{ __('Division Head Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow rounded border border-gray-100">
                <div class="p-4">
                    <div class="mb-4 pb-2 border-b border-gray-200">
                        <h1 class="text-lg font-bold text-gray-800">Welcome, {{ Auth::user()->employee->first_name ?? Auth::user()->name }}!</h1>
                        <p class="text-gray-600 text-sm mt-1">Division Head dashboard for overseeing vehicle requests and departmental approvals.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Department Requests -->
                        <div class="bg-green-50 rounded-lg p-3 border border-green-100">
                            <div class="flex items-center mb-2">
                                <div class="rounded-lg bg-[#1e6031] p-2 mr-3 flex-shrink-0">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-800">Department Requests</h3>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">View all vehicle requests from units within your division.</p>
                            <a href="#" class="inline-flex items-center px-3 py-1.5 bg-white border border-green-300 text-green-800 rounded-md hover:bg-green-100 focus:outline-none focus:ring-1 focus:ring-green-500 focus:ring-offset-1 text-xs font-medium transition duration-200 shadow-sm">
                                View Requests
                                <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                        
                        <!-- Pending Approvals -->
                        <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-100">
                            <div class="flex items-center mb-2">
                                <div class="rounded-lg bg-yellow-500 p-2 mr-3 flex-shrink-0">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-800">Pending Approvals</h3>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">Review and approve vehicle requests from division heads.</p>
                            <a href="#" class="inline-flex items-center px-3 py-1.5 bg-white border border-yellow-300 text-yellow-800 rounded-md hover:bg-yellow-100 focus:outline-none focus:ring-1 focus:ring-yellow-500 focus:ring-offset-1 text-xs font-medium transition duration-200 shadow-sm">
                                Review Now
                                <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                        
                        <!-- Division Calendar -->
                        <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                            <div class="flex items-center mb-2">
                                <div class="rounded-lg bg-blue-600 p-2 mr-3 flex-shrink-0">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-800">Division Schedule</h3>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">View vehicle assignments and schedules for your division.</p>
                            <a href="{{ route('vehicle-calendar.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-blue-300 text-blue-800 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 text-xs font-medium transition duration-200 shadow-sm">
                                Open Calendar
                                <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Recent Activity -->
                    <div class="mt-6">
                        <h3 class="text-base font-semibold text-gray-800 mb-3">Recent Activity</h3>
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="text-center py-6">
                                <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-500 text-sm">No recent activity to display</p>
                                <p class="text-gray-400 text-xs mt-1">Recent approvals and division activities will appear here</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection