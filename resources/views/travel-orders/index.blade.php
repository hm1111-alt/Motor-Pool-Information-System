@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('My Travel Requests') }}
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Page Header -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b border-gray-200">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">My Travel Requests</h1>
                            <p class="text-gray-600 mt-1">Manage your travel requests and view their status</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('travel-orders.create') }}" class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#1e6031] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Create New Request
                            </a>
                        </div>
                    </div>
                    

                    
                    <!-- Tabs -->
                    <div class="mb-6 border-b border-gray-200">
                        <nav class="flex space-x-6" aria-label="Tabs">
                            <a href="{{ route('travel-orders.index', ['tab' => 'pending']) }}" 
                               class="{{ (isset($tab) && $tab == 'pending') || !isset($tab) ? 'border-[#1e6031] text-[#1e6031] font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 text-sm transition-colors duration-200"
                               data-tab-switch="pending">
                                Pending
                            </a>
                            <a href="{{ route('travel-orders.index', ['tab' => 'approved']) }}" 
                               class="{{ isset($tab) && $tab == 'approved' ? 'border-[#1e6031] text-[#1e6031] font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 text-sm transition-colors duration-200"
                               data-tab-switch="approved">
                                Approved
                            </a>
                            <a href="{{ route('travel-orders.index', ['tab' => 'cancelled']) }}" 
                               class="{{ isset($tab) && $tab == 'cancelled' ? 'border-[#1e6031] text-[#1e6031] font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 text-sm transition-colors duration-200"
                               data-tab-switch="cancelled">
                                Cancelled
                            </a>
                        </nav>
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
                                   id="search-input" 
                                   class="table-search-input focus:ring-[#1e6031] focus:border-[#1e6031] block w-full pl-10 pr-12 py-2 sm:text-sm border-gray-300 rounded-lg"
                                   placeholder="Search destination or purpose..."
                                   data-table-id="travel-orders-table"
                                   data-url="{{ route('travel-orders.index') }}"
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
                    
                    <!-- Travel Orders Table -->
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <div class="overflow-x-auto">
                            <table id="travel-orders-table" class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Range</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departure Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                        @if((isset($tab) && $tab == 'pending') || !isset($tab))
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @include('travel-orders.partials.table-rows', ['travelOrders' => $travelOrders, 'tab' => $tab ?? 'pending'])
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    @if($travelOrders->hasPages())
                        <div class="mt-4">
                            {{ $travelOrders->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include the table search JavaScript -->
    <script src="{{ asset('js/table-search.js') }}"></script>
@endsection