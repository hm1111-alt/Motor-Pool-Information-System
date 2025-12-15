@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-lg text-gray-800 leading-tight">
        {{ __('Approve Travel Orders') }}
    </h2>
@endsection

@section('content')
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow rounded border border-gray-100">
                <div class="p-4">
                    <div class="mb-4 pb-2 border-b border-gray-200">
                        <h1 class="text-lg font-bold text-gray-800">Travel Orders for Approval</h1>
                        <p class="text-gray-600 text-sm mt-1">Review and approve travel requests from your unit members.</p>
                    </div>
                    

                    
                    <!-- Tabs -->
                    <div class="mb-4 border-b border-gray-200">
                        <nav class="flex space-x-8" aria-label="Tabs">
                            <a href="{{ route('travel-orders.approvals.head', ['tab' => 'pending']) }}" 
                               class="{{ (isset($tab) && $tab == 'pending') || !isset($tab) ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                               data-tab-switch="pending">
                                Pending
                            </a>
                            <a href="{{ route('travel-orders.approvals.head', ['tab' => 'approved']) }}" 
                               class="{{ isset($tab) && $tab == 'approved' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                               data-tab-switch="approved">
                                Approved
                            </a>
                            <a href="{{ route('travel-orders.approvals.head', ['tab' => 'cancelled']) }}" 
                               class="{{ isset($tab) && $tab == 'cancelled' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                               data-tab-switch="cancelled">
                                Cancelled
                            </a>
                        </nav>
                    </div>
                    
                    @if(session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-3 border border-green-200">
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
                                   data-table-id="approval-table"
                                   data-url="{{ route('travel-orders.approvals.head') }}"
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
                        <table id="approval-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Range</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                    @if((isset($tab) && $tab == 'pending') || !isset($tab))
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @include('travel-orders.approvals.partials.table-rows', ['travelOrders' => $travelOrders, 'tab' => $tab ?? 'pending'])
                            </tbody>
                        </table>
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