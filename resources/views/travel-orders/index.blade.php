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
                               class="{{ (isset($tab) && $tab == 'pending') || !isset($tab) ? 'border-[#1e6031] text-[#1e6031] font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 text-sm transition-colors duration-200">
                                Pending
                            </a>
                            <a href="{{ route('travel-orders.index', ['tab' => 'approved']) }}" 
                               class="{{ isset($tab) && $tab == 'approved' ? 'border-[#1e6031] text-[#1e6031] font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 text-sm transition-colors duration-200">
                                Approved
                            </a>
                            <a href="{{ route('travel-orders.index', ['tab' => 'cancelled']) }}" 
                               class="{{ isset($tab) && $tab == 'cancelled' ? 'border-[#1e6031] text-[#1e6031] font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 text-sm transition-colors duration-200">
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
                    
                    <!-- Travel Orders Table -->
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-300">
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
                                    @forelse($travelOrders as $travelOrder)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $travelOrder->destination }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $travelOrder->date_from->format('M d, Y') }} - {{ $travelOrder->date_to->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($travelOrder->departure_time)
                                                    {{ date('g:i A', strtotime($travelOrder->departure_time)) }}
                                                @else
                                                    <span class="text-gray-400">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $travelOrder->purpose }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($travelOrder->status === 'approved') bg-green-100 text-green-800
                                                    @elseif($travelOrder->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($travelOrder->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $travelOrder->remarks }}
                                            </td>
                                            @if((isset($tab) && $tab == 'pending') || !isset($tab))
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('travel-orders.show', $travelOrder) }}" class="text-[#1e6031] hover:text-[#164f2a] mr-3">View</a>
                                                    @if((!$travelOrder->head_approved && !$travelOrder->vp_approved) || $travelOrder->employee->is_president)
                                                        <a href="{{ route('travel-orders.edit', $travelOrder) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                                        <form action="{{ route('travel-orders.destroy', $travelOrder) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this travel order?')">Delete</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ (isset($tab) && $tab == 'pending') || !isset($tab) ? '7' : '6' }}" class="px-6 py-8 text-center text-sm text-gray-500">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                    <div class="mt-2">
                                                        @if(isset($tab))
                                                            @switch($tab)
                                                                @case('pending')
                                                                    <h3 class="text-lg font-medium text-gray-900">No pending travel requests</h3>
                                                                    <p class="mt-1">You don't have any pending travel requests at the moment.</p>
                                                                    @break
                                                                @case('approved')
                                                                    <h3 class="text-lg font-medium text-gray-900">No approved travel requests</h3>
                                                                    <p class="mt-1">You don't have any approved travel requests yet.</p>
                                                                    @break
                                                                @case('cancelled')
                                                                    <h3 class="text-lg font-medium text-gray-900">No cancelled travel requests</h3>
                                                                    <p class="mt-1">You don't have any cancelled travel requests.</p>
                                                                    @break
                                                                @default
                                                                    <h3 class="text-lg font-medium text-gray-900">No travel requests found</h3>
                                                                    <p class="mt-1">There are no travel requests matching your criteria.</p>
                                                            @endswitch
                                                        @else
                                                            <h3 class="text-lg font-medium text-gray-900">No pending travel requests</h3>
                                                            <p class="mt-1">You don't have any pending travel requests at the moment.</p>
                                                        @endif
                                                    </div>
                                                </div>
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
    </div>
@endsection