@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('VP Trip Ticket Approvals') }}
    </h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">VP Trip Ticket Approvals</h1>
                        <p class="text-gray-600 mt-1">Review and approve pending trip tickets.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('motorpool.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <a href="{{ request()->url() }}?tab=pending" 
                       class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $tab === 'pending' ? 'border-indigo-500 text-indigo-600' : '' }}">
                        Pending ({{ $pendingCount }})
                    </a>
                    <a href="{{ request()->url() }}?tab=approved" 
                       class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $tab === 'approved' ? 'border-indigo-500 text-indigo-600' : '' }}">
                        Approved ({{ $approvedCount }})
                    </a>
                    <a href="{{ request()->url() }}?tab=cancelled" 
                       class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $tab === 'cancelled' ? 'border-indigo-500 text-indigo-600' : '' }}">
                        Cancelled ({{ $cancelledCount }})
                    </a>
                </nav>
            </div>
        </div>

        <!-- Trip Tickets Table -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                @if($tripTickets->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No trip tickets found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if($tab === 'pending')
                                There are no pending trip tickets requiring your approval.
                            @elseif($tab === 'approved')
                                No trip tickets have been approved yet.
                            @else
                                No trip tickets have been cancelled.
                            @endif
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ticket Number
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Employee
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Destination
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Vehicle
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tripTickets as $ticket)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $ticket->ticket_number ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $ticket->itinerary?->travelOrder?->employee?->first_name ?? 'N/A' }} 
                                            {{ $ticket->itinerary?->travelOrder?->employee?->last_name ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $ticket->itinerary?->destination ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $ticket->itinerary?->date_from?->format('M d, Y') ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $ticket->itinerary?->vehicle?->make ?? 'N/A' }} 
                                            {{ $ticket->itinerary?->vehicle?->model ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @if($ticket->status === 'Pending') bg-yellow-100 text-yellow-800
                                                @elseif($ticket->status === 'Approved') bg-green-100 text-green-800
                                                @elseif($ticket->status === 'Cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $ticket->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('vp.trip-tickets.approvals.show', $ticket) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                View
                                            </a>
                                            @if($tab === 'pending')
                                                <form action="{{ route('vp.trip-tickets.approvals.approve', $ticket) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-green-600 hover:text-green-900 mr-3"
                                                            onclick="return confirm('Are you sure you want to approve this trip ticket?')">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('vp.trip-tickets.approvals.reject', $ticket) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Are you sure you want to reject this trip ticket?')">
                                                        Reject
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection