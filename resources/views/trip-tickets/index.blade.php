@extends('layouts.motorpool-admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-3">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-4 w-4 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="main">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-gray-800">Trip Tickets</h2>
                
                <div class="flex items-center gap-3">
                    <form id="searchForm" method="GET" action="{{ route('trip-tickets.index') }}" class="flex">
                        <input type="text" name="search" 
                               class="px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:border-[#1e6031] text-sm"
                               placeholder="Search trip tickets..." 
                               value="{{ request('search') }}"
                               style="width: 250px;">
                        <button type="submit" 
                                class="bg-[#1e6031] text-white px-3 py-2 rounded-r-md hover:bg-[#164f2a] transition duration-200">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    
                    <a href="{{ route('trip-tickets.create') }}" 
                       class="bg-[#1e6031] text-white px-4 py-2 rounded-md hover:bg-[#164f2a] transition duration-200 flex items-center text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i> Create Trip Ticket
                    </a>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex space-x-8">
                    <button type="button" 
                            class="tab-button pb-3 px-1 border-b-2 font-medium text-sm {{ ($tab ?? 'pending') === 'pending' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                            data-tab="pending">
                        Pending
                    </button>
                    <button type="button" 
                            class="tab-button pb-3 px-1 border-b-2 font-medium text-sm {{ ($tab ?? 'pending') === 'ongoing' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                            data-tab="ongoing">
                        On-going
                    </button>
                    <button type="button" 
                            class="tab-button pb-3 px-1 border-b-2 font-medium text-sm {{ ($tab ?? 'pending') === 'completed' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                            data-tab="completed">
                        Completed
                    </button>
                </nav>
            </div>

            <!-- Pending Tab -->
            <div class="tab-content" id="pending-tab">
                @if($pendingTripTickets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead style="background-color: #1e6031; color: white;">
                                <tr>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">No.</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Driver</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Head of the Party</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Vehicle</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Destination</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Date</th>
                                    <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pending-table-body">
                                @foreach($pendingTripTickets as $index => $ticket)
                                    <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-[#f0f8f0]">
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">{{ $loop->iteration }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">
                                            @if($ticket->itinerary && $ticket->itinerary->driver)
                                                {{ $ticket->itinerary->driver->full_name }}
                                            @else
                                                <span class="text-gray-500">No driver</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                            {{ $ticket->head_of_party ?? 'Not set' }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                            @if($ticket->itinerary && $ticket->itinerary->vehicle)
                                                <div>{{ $ticket->itinerary->vehicle->make }} {{ $ticket->itinerary->vehicle->model }}</div>
                                                <div class="text-gray-500 text-xs">Plate: {{ $ticket->itinerary->vehicle->plate_number }}</div>
                                            @else
                                                <span class="text-gray-500">No vehicle</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                            @if($ticket->itinerary)
                                                {{ $ticket->itinerary->destination }}
                                            @else
                                                <span class="text-gray-500">No destination</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                            @if($ticket->itinerary && $ticket->itinerary->date_from)
                                                {{ \Carbon\Carbon::parse($ticket->itinerary->date_from)->format('M d, Y') }}
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-center text-xs">
                                            <div class="action-buttons flex justify-center space-x-1">
                                                <button type="button" 
                                                        class="btn view-btn border inline-flex items-center justify-center"
                                                        title="View Itinerary PDF"
                                                        onclick="viewItineraryPDF({{ $ticket->itinerary->id ?? 0 }})">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <button type="button" 
                                                        class="btn pdf-btn border inline-flex items-center justify-center"
                                                        title="View as PDF"
                                                        onclick="viewTripTicketPDF({{ $ticket->id }})">
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </button>
                                                <a href="{{ route('trip-tickets.edit', $ticket) }}" 
                                                   class="btn edit-btn border inline-flex items-center justify-center"
                                                   title="Edit Trip Ticket">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" 
                                                        class="btn start-btn border inline-flex items-center justify-center"
                                                        title="Start Trip"
                                                        onclick="startTrip({{ $ticket->id }})">
                                                    <i class="fas fa-play"></i> Start
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="flex justify-between items-center mt-4">
                        <div class="text-sm text-gray-600">
                            Showing {{ $pendingTripTickets->firstItem() ?? 0 }} to {{ $pendingTripTickets->lastItem() ?? 0 }} of {{ $pendingTripTickets->total() }} applications
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($pendingTripTickets->lastPage() > 1)
                                {{ $pendingTripTickets->appends(['search' => request('search'), 'tab' => 'pending'])->links() }}
                            @else
                                <nav>
                                    <ul class="pagination">
                                        <li class="page-item disabled">
                                            <span class="page-link">Prev</span>
                                        </li>
                                        <li class="page-item active">
                                            <span class="page-link">1</span>
                                        </li>
                                        <li class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </li>
                                    </ul>
                                </nav>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No pending trip tickets</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new trip ticket.</p>
                    </div>
                @endif
            </div>

            <!-- On-going Tab -->
            <div class="tab-content hidden" id="ongoing-tab">
                @if($ongoingTripTickets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead style="background-color: #1e6031; color: white;">
                                <tr>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">No.</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Driver</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Head of the Party</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Vehicle</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Destination</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Date</th>
                                    <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ongoing-table-body">
                                @foreach($ongoingTripTickets as $index => $ticket)
                                    <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-[#f0f8f0]">
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">{{ $loop->iteration }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">
                                            @if($ticket->itinerary && $ticket->itinerary->driver)
                                                {{ $ticket->itinerary->driver->full_name }}
                                            @else
                                                <span class="text-gray-500">No driver</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                            {{ $ticket->head_of_party ?? 'Not set' }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                            @if($ticket->itinerary && $ticket->itinerary->vehicle)
                                                <div>{{ $ticket->itinerary->vehicle->make }} {{ $ticket->itinerary->vehicle->model }}</div>
                                                <div class="text-gray-500 text-xs">Plate: {{ $ticket->itinerary->vehicle->plate_number }}</div>
                                            @else
                                                <span class="text-gray-500">No vehicle</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                            @if($ticket->itinerary)
                                                {{ $ticket->itinerary->destination }}
                                            @else
                                                <span class="text-gray-500">No destination</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                            @if($ticket->itinerary && $ticket->itinerary->date_from)
                                                {{ \Carbon\Carbon::parse($ticket->itinerary->date_from)->format('M d, Y') }}
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-center text-xs">
                                            <div class="action-buttons flex justify-center space-x-1">
                                                <button type="button" 
                                                        class="btn view-btn border inline-flex items-center justify-center"
                                                        title="View Itinerary PDF"
                                                        onclick="viewItineraryPDF({{ $ticket->itinerary->id ?? 0 }})">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <button type="button" 
                                                        class="btn pdf-btn border inline-flex items-center justify-center"
                                                        title="View as PDF"
                                                        onclick="viewTripTicketPDF({{ $ticket->id }})">
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </button>
                                                <a href="{{ route('trip-tickets.edit', $ticket) }}" 
                                                   class="btn edit-btn border inline-flex items-center justify-center"
                                                   title="Edit Trip Ticket">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" 
                                                        class="btn end-btn border inline-flex items-center justify-center"
                                                        title="End Trip"
                                                        onclick="endTrip({{ $ticket->id }})">
                                                    <i class="fas fa-stop"></i> End
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="flex justify-between items-center mt-4">
                        <div class="text-sm text-gray-600">
                            Showing {{ $ongoingTripTickets->firstItem() ?? 0 }} to {{ $ongoingTripTickets->lastItem() ?? 0 }} of {{ $ongoingTripTickets->total() }} applications
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($ongoingTripTickets->lastPage() > 1)
                                {{ $ongoingTripTickets->appends(['search' => request('search'), 'tab' => 'ongoing'])->links() }}
                            @else
                                <nav>
                                    <ul class="pagination">
                                        <li class="page-item disabled">
                                            <span class="page-link">Prev</span>
                                        </li>
                                        <li class="page-item active">
                                            <span class="page-link">1</span>
                                        </li>
                                        <li class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </li>
                                    </ul>
                                </nav>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No ongoing trip tickets</h3>
                        <p class="mt-1 text-sm text-gray-500">There are currently no ongoing trip tickets.</p>
                    </div>
                @endif
            </div>

            <!-- Completed Tab -->
            <div class="tab-content hidden" id="completed-tab">
                @if($completedTripTickets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead style="background-color: #1e6031; color: white;">
                                <tr>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">No.</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Driver</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Head of the Party</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Vehicle</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Destination</th>
                                    <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Date</th>
                                    <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="completed-table-body">
                                @foreach($completedTripTickets as $index => $ticket)
                                    <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-[#f0f8f0]">
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">{{ $loop->iteration }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">
                                            @if($ticket->itinerary && $ticket->itinerary->driver)
                                                {{ $ticket->itinerary->driver->full_name }}
                                            @else
                                                <span class="text-gray-500">No driver</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                            {{ $ticket->head_of_party ?? 'Not set' }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                            @if($ticket->itinerary && $ticket->itinerary->vehicle)
                                                <div>{{ $ticket->itinerary->vehicle->make }} {{ $ticket->itinerary->vehicle->model }}</div>
                                                <div class="text-gray-500 text-xs">Plate: {{ $ticket->itinerary->vehicle->plate_number }}</div>
                                            @else
                                                <span class="text-gray-500">No vehicle</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                            @if($ticket->itinerary)
                                                {{ $ticket->itinerary->destination }}
                                            @else
                                                <span class="text-gray-500">No destination</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                            @if($ticket->itinerary && $ticket->itinerary->date_from)
                                                {{ \Carbon\Carbon::parse($ticket->itinerary->date_from)->format('M d, Y') }}
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-center text-xs">
                                            <div class="action-buttons flex justify-center space-x-1">
                                                <button type="button" 
                                                        class="btn view-btn border inline-flex items-center justify-center"
                                                        title="View Itinerary PDF"
                                                        onclick="viewItineraryPDF({{ $ticket->itinerary->id ?? 0 }})">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <button type="button" 
                                                        class="btn pdf-btn border inline-flex items-center justify-center"
                                                        title="View as PDF"
                                                        onclick="viewTripTicketPDF({{ $ticket->id }})">
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </button>
                                                <button type="button" 
                                                        class="btn archive-btn border inline-flex items-center justify-center"
                                                        title="Archive Trip"
                                                        onclick="archiveTrip({{ $ticket->id }})">
                                                    <i class="fas fa-archive"></i> Archive
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="flex justify-between items-center mt-4">
                        <div class="text-sm text-gray-600">
                            Showing {{ $completedTripTickets->firstItem() ?? 0 }} to {{ $completedTripTickets->lastItem() ?? 0 }} of {{ $completedTripTickets->total() }} applications
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($completedTripTickets->lastPage() > 1)
                                {{ $completedTripTickets->appends(['search' => request('search'), 'tab' => 'completed'])->links() }}
                            @else
                                <nav>
                                    <ul class="pagination">
                                        <li class="page-item disabled">
                                            <span class="page-link">Prev</span>
                                        </li>
                                        <li class="page-item active">
                                            <span class="page-link">1</span>
                                        </li>
                                        <li class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </li>
                                    </ul>
                                </nav>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No completed trip tickets</h3>
                        <p class="mt-1 text-sm text-gray-500">There are currently no completed trip tickets.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.action-buttons .btn {
    font-size: 10px;
    padding: 2px 6px;
    line-height: 1;
    height: 25px;
    min-width: 50px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
    border-radius: 4px;
}

.action-buttons .btn i {
    font-size: 10px;
    margin-right: 2px;
}

.action-buttons .view-btn {
    color: #0d6efd !important;
    border: 1px solid #0d6efd !important;
    background-color: transparent !important;
}

.action-buttons .view-btn:hover {
    background-color: #0d6efd !important;
    color: #fff !important;
    border-color: #0d6efd !important;
}

.action-buttons .edit-btn {
    color: #ffc107 !important;
    border: 1px solid #ffc107 !important;
    background-color: transparent !important;
}

.action-buttons .edit-btn:hover {
    background-color: #ffc107 !important;
    color: #000 !important;
    border-color: #ffc107 !important;
}

.action-buttons .start-btn {
    color: #198754 !important;
    border: 1px solid #198754 !important;
    background-color: transparent !important;
}

.action-buttons .start-btn:hover {
    background-color: #198754 !important;
    color: #fff !important;
    border-color: #198754 !important;
}

.action-buttons .end-btn {
    color: #fd7e14 !important;
    border: 1px solid #fd7e14 !important;
    background-color: transparent !important;
}

.action-buttons .end-btn:hover {
    background-color: #fd7e14 !important;
    color: #fff !important;
    border-color: #fd7e14 !important;
}

.action-buttons .archive-btn {
    color: #dc3545 !important;
    border: 1px solid #dc3545 !important;
    background-color: transparent !important;
}

.action-buttons .archive-btn:hover {
    background-color: #dc3545 !important;
    color: #fff !important;
    border-color: #dc3545 !important;
}

.action-buttons .pdf-btn {
    color: #dc3545 !important;
    border: 1px solid #dc3545 !important;
    background-color: transparent !important;
}

.action-buttons .pdf-btn:hover {
    background-color: #dc3545 !important;
    color: #fff !important;
    border-color: #dc3545 !important;
}

/* Pagination styling to match drivers/vehicles */
.pagination {
    display: flex;
    justify-content: flex-end;
    list-style: none;
}

.pagination .page-link {
    color: #1e6031;
    border-color: #1e6031;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    display: block;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #1e6031;
    border-radius: 0.25rem;
}

.pagination .page-link:hover {
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
}

.pagination .active .page-link {
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
}

.page-item:first-child .page-link,
.page-item:last-child .page-link {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
    border-radius: 0.25rem;
}

.page-item:first-child .page-link:hover,
.page-item:last-child .page-link:hover {
    background-color: #164f2a;
    border-color: #164f2a;
}

.page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
    padding: 0.25rem 0.5rem;
}

.page-item {
    margin: 0 2px;
}

.page-item.active .page-link {
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
    padding: 0.25rem 0.5rem;
}

.page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
    padding: 0.25rem 0.5rem;
    cursor: not-allowed;
}
</style>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    // Set first tab as active by default
    if(tabContents.length > 0) {
        document.getElementById('pending-tab').classList.remove('hidden');
        tabButtons[0].classList.add('border-[#1e6031]', 'text-[#1e6031]');
        tabButtons[0].classList.remove('border-transparent', 'text-gray-500');
    }
    
    tabButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('border-[#1e6031]', 'text-[#1e6031]');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Add active class to clicked button
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-[#1e6031]', 'text-[#1e6031]');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.remove('hidden');
        });
    });
    
    // Handle search form submission
    const searchForm = document.getElementById('searchForm');
    if(searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = document.querySelector('[name="search"]');
            if(searchInput && searchInput.value.trim() === '') {
                window.location.href = '{{ route("trip-tickets.index") }}';
                e.preventDefault();
            }
        });
    }
});

// Function to start a trip
function startTrip(ticketId) {
    if(confirm('Are you sure you want to start this trip?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/trip-tickets/${ticketId}/status`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;
        form.appendChild(tokenInput);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = 'Approved';
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Function to end a trip
function endTrip(ticketId) {
    if(confirm('Are you sure you want to end this trip?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/trip-tickets/${ticketId}/status`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;
        form.appendChild(tokenInput);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = 'Completed';
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Function to archive a trip
function archiveTrip(ticketId) {
    if(confirm('Are you sure you want to archive this trip?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/trip-tickets/${ticketId}/status`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;
        form.appendChild(tokenInput);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = 'Archived';
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Function to view itinerary as PDF
function viewItineraryPDF(itineraryId) {
    if (!itineraryId) {
        alert('No itinerary found for this trip ticket.');
        return;
    }
    // Open the PDF in a new tab
    window.open(`/itinerary/${itineraryId}/pdf`, '_blank');
}

// Function to view trip ticket as PDF
function viewTripTicketPDF(ticketId) {
    // Open the PDF in a new tab
    window.open(`/trip-tickets/${ticketId}/pdf`, '_blank');
}
</script>
@endsection