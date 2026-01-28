@extends('layouts.employee')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800">Trip Ticket Approvals</h2>
            <p class="mt-1 text-gray-600">Manage and approve trip tickets requiring VP approval</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="{{ route('vp.trip-tickets.approvals.index', ['tab' => 'pending']) }}"
                   class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $tab == 'pending' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pending
                    @if($tab == 'pending')
                        <span class="ml-2 bg-[#1e6031] text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $tripTickets->total() }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('vp.trip-tickets.approvals.index', ['tab' => 'approved']) }}"
                   class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $tab == 'approved' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Approved
                    @if($tab == 'approved')
                        <span class="ml-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $tripTickets->total() }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('vp.trip-tickets.approvals.index', ['tab' => 'cancelled']) }}"
                   class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $tab == 'cancelled' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Cancelled
                    @if($tab == 'cancelled')
                        <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $tripTickets->total() }}
                        </span>
                    @endif
                </a>
            </nav>
        </div>

        <!-- Search Form -->
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('vp.trip-tickets.approvals.index', ['tab' => $tab]) }}" class="flex items-center">
                <div class="flex-1 max-w-lg">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search trip tickets..." 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                </div>
                <button type="submit" class="ml-2 bg-[#1e6031] hover:bg-[#164f2a] text-white px-4 py-2 rounded-lg transition duration-300">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('vp.trip-tickets.approvals.index', ['tab' => $tab]) }}" 
                       class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Trip Tickets Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tripTickets as $tripTicket)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $tripTicket->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $tripTicket->itinerary->travelOrder->employee->first_name ?? 'N/A' }} {{ $tripTicket->itinerary->travelOrder->employee->last_name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $tripTicket->itinerary->destination ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $tripTicket->itinerary->purpose ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($tripTicket->status == 'Pending') bg-yellow-100 text-yellow-800
                                    @elseif($tripTicket->status == 'Issued') bg-green-100 text-green-800
                                    @elseif($tripTicket->status == 'Completed') bg-blue-100 text-blue-800
                                    @elseif($tripTicket->status == 'Cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $tripTicket->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($tab == 'pending')
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('vp.trip-tickets.approvals.show', $tripTicket->id) }}" 
                                           class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            View
                                        </a>
                                        <a href="{{ route('vp.trip-tickets.approvals.approve', $tripTicket->id) }}" 
                                           onclick="event.preventDefault(); if(confirm('Are you sure you want to approve this trip ticket?')) document.getElementById('approve-form-{{ $tripTicket->id }}').submit();"
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Approve
                                        </a>
                                        <a href="{{ route('vp.trip-tickets.approvals.reject', $tripTicket->id) }}" 
                                           onclick="event.preventDefault(); if(confirm('Are you sure you want to reject this trip ticket?')) document.getElementById('reject-form-{{ $tripTicket->id }}').submit();"
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Reject
                                        </a>
                                    </div>
                                    <form id="approve-form-{{ $tripTicket->id }}" action="{{ route('vp.trip-tickets.approvals.approve', $tripTicket->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                    <form id="reject-form-{{ $tripTicket->id }}" action="{{ route('vp.trip-tickets.approvals.reject', $tripTicket->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                @else
                                    <div class="flex justify-end">
                                        <a href="{{ route('vp.trip-tickets.approvals.show', $tripTicket->id) }}" 
                                           class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            View
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                No trip tickets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($tripTickets->hasPages())
            <div class="px-6 py-3 bg-white border-t border-gray-200">
                {{ $tripTickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection