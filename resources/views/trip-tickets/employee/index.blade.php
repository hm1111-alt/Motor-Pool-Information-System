@extends('layouts.employee')

@section('title', 'My Trip Tickets')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                My Trip Tickets
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                View trip tickets where you are listed as a passenger or head of party.
            </p>
        </div>
        
        <!-- Search and Filter -->
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <!-- Search Form -->
                <form method="GET" action="{{ route('employee.trip-tickets.index') }}" class="flex-1 max-w-md">
                    <div class="relative rounded-md shadow-sm">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               placeholder="Search trip tickets..." 
                               class="focus:ring-[#1e6031] focus:border-[#1e6031] block w-full pr-10 sm:text-sm border-gray-300 rounded-md">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <a href="{{ route('employee.trip-tickets.index', ['tab' => 'approved']) }}"
                       class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $tab == 'approved' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Approved
                        @if($tab == 'approved')
                            <span class="ml-2 bg-[#1e6031] text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ $tripTickets->total() }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('employee.trip-tickets.index', ['tab' => 'completed']) }}"
                       class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $tab == 'completed' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Completed
                        @if($tab == 'completed')
                            <span class="ml-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ $tripTickets->total() }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('employee.trip-tickets.index', ['tab' => 'cancelled']) }}"
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
        </div>
        
        <!-- Trip Tickets Table -->
        <div class="px-4 py-5 sm:p-6">
            @if($tripTickets->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No trip tickets found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($search)
                            No trip tickets match your search "{{ $search }}".
                        @else
                            You don't have any trip tickets in the "{{ $tab }}" status.
                        @endif
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Destination
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dates
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vehicle
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Head of Party
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tripTickets as $tripTicket)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                            {{ $tripTicket->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $tripTicket->status === 'Approved' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $tripTicket->status === 'Completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $tripTicket->status === 'Cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $tripTicket->status === 'Archived' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ $tripTicket->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $tripTicket->itinerary->destination ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $tripTicket->itinerary->purpose ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($tripTicket->itinerary->date_from && $tripTicket->itinerary->date_to)
                                            {{ $tripTicket->itinerary->date_from->format('M d, Y') }} - {{ $tripTicket->itinerary->date_to->format('M d, Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $tripTicket->itinerary->vehicle->plate_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $tripTicket->head_of_party }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end">
                                            <a href="{{ route('employee.trip-tickets.show', $tripTicket->id) }}" 
                                               class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $tripTickets->appends(['tab' => $tab, 'search' => $search])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection