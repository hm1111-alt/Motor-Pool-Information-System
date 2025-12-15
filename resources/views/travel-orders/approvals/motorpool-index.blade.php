@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Approved Travel Orders') }}
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="mb-6 pb-4 border-b border-gray-200">
                        <h1 class="text-2xl font-bold text-gray-800">Approved Travel Orders</h1>
                        <p class="text-gray-600 mt-1">Manage vehicle assignments for approved travel requests.</p>
                    </div>
                    

                    
                    @if(session('success'))
                        <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   class="table-search-input focus:ring-[#1e6031] focus:border-[#1e6031] block w-full pl-10 pr-12 py-2 sm:text-sm border-gray-300 rounded-lg"
                                   placeholder="Search employee, destination or purpose..."
                                   data-table-id="motorpool-table"
                                   data-url="{{ route('approved-travel-orders.index') }}"
                                   value="{{ $search ?? '' }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                @if(!empty($search))
                                    <button type="button" class="clear-search text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table id="motorpool-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Range</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departure Time</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @include('travel-orders.approvals.partials.motorpool-table-rows', ['travelOrders' => $travelOrders])
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($travelOrders->hasPages())
                        <div class="mt-4">
                            {{ $travelOrders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include the table search JavaScript -->
    <script src="{{ asset('js/table-search.js') }}"></script>
@endsection