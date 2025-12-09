@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-lg text-gray-800 leading-tight">
        {{ __('VP Travel Order Approvals') }}
    </h2>
@endsection

@section('content')
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow rounded border border-gray-100">
                <div class="p-4">
                    <div class="mb-4 pb-2 border-b border-gray-200">
                        <h1 class="text-lg font-bold text-gray-800">Travel Orders for Approval</h1>
                        <p class="text-gray-600 text-sm mt-1">Review and approve travel requests that have been approved by unit heads.</p>
                    </div>
                    
                    <!-- Tabs -->
                    <div class="mb-4 border-b border-gray-200">
                        <nav class="flex space-x-8" aria-label="Tabs">
                            <a href="{{ route('travel-orders.approvals.vp', ['tab' => 'pending']) }}" 
                               class="{{ (isset($tab) && $tab == 'pending') || !isset($tab) ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                Pending
                            </a>
                            <a href="{{ route('travel-orders.approvals.vp', ['tab' => 'approved']) }}" 
                               class="{{ isset($tab) && $tab == 'approved' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                Approved
                            </a>
                            <a href="{{ route('travel-orders.approvals.vp', ['tab' => 'cancelled']) }}" 
                               class="{{ isset($tab) && $tab == 'cancelled' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
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
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Range</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Head Approved</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($travelOrders as $travelOrder)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                            {{ $travelOrder->employee->first_name }} {{ $travelOrder->employee->last_name }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                            {{ $travelOrder->employee->unit->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $travelOrder->destination }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                            {{ $travelOrder->date_from->format('M d, Y') }} - {{ $travelOrder->date_to->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-900 max-w-xs truncate">{{ $travelOrder->purpose }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                            {{ $travelOrder->head_approved_at->format('M d, Y g:i A') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                            {{ $travelOrder->remarks }}
                                        </td>
                                        @if((isset($tab) && $tab == 'pending') || !isset($tab))
                                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('travel-orders.show', $travelOrder) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">View</a>
                                                
                                                <form action="{{ route('travel-orders.approve.vp', $travelOrder) }}" method="POST" class="inline-block mr-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Are you sure you want to approve this travel order?')">Approve</button>
                                                </form>
                                                
                                                <form action="{{ route('travel-orders.reject.vp', $travelOrder) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to reject this travel order?')">Reject</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ (isset($tab) && $tab == 'pending') || !isset($tab) ? '8' : '7' }}" class="px-4 py-4 text-center text-sm text-gray-500">
                                            @if(isset($tab))
                                                @switch($tab)
                                                    @case('pending')
                                                        No travel orders pending your approval.
                                                        @break
                                                    @case('approved')
                                                        No approved travel orders found.
                                                        @break
                                                    @case('cancelled')
                                                        No cancelled travel orders found.
                                                        @break
                                                    @default
                                                        No travel orders found.
                                                @endswitch
                                            @else
                                                No travel orders pending your approval.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection