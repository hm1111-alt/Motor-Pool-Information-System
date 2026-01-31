@extends('layouts.motorpool-admin')



@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white shadow mb-6">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            TRIP TICKETS MANAGEMENT
                        </h2>
                        <a href="{{ route('trip-tickets.create') }}" class="inline-flex items-center px-6 py-3 bg-[#1e6031] border border-transparent rounded-md font-bold text-lg text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            CREATE TRIP TICKET
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search Form -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('trip-tickets.index') }}" class="flex gap-2">
                            <div class="flex-grow">
                                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search trip tickets..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Search
                            </button>
                            @if($search ?? false)
                                <a href="{{ route('trip-tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Clear
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-6 rounded-md bg-green-50 p-4">
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

                    <!-- Trip Tickets Tabs -->
                    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Trip Tickets</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage trip tickets by status</p>
                        </div>
                        
                        <!-- Tabs -->
                        <div class="border-b border-gray-200">
                            <nav class="flex -mb-px space-x-8 px-4">
                                <button type="button" class="tab-button active:bg-[#1e6031] active:text-white active:border-[#1e6031] border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="pending">
                                    Pending ({{ $pendingTripTickets->total() }})
                                </button>
                                <button type="button" class="tab-button active:bg-[#1e6031] active:text-white active:border-[#1e6031] border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="ongoing">
                                    Ongoing ({{ $ongoingTripTickets->total() }})
                                </button>
                                <button type="button" class="tab-button active:bg-[#1e6031] active:text-white active:border-[#1e6031] border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="completed">
                                    Completed ({{ $completedTripTickets->total() }})
                                </button>
                            </nav>
                        </div>
                        
                        <!-- Tab Content -->
                        <div class="overflow-x-auto">
                            <!-- Pending Tab Content -->
                            <div id="pending-tab" class="tab-content">
                                @if($pendingTripTickets->count() > 0)
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Head of Party</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($pendingTripTickets as $ticket)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->id }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->head_of_party ?? 'Not set' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary && $ticket->itinerary->driver)
                                                            {{ $ticket->itinerary->driver->full_name }}
                                                        @else
                                                            <span class="text-gray-500">No driver</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary && $ticket->itinerary->vehicle)
                                                            <div class="font-medium">{{ $ticket->itinerary->vehicle->make }} {{ $ticket->itinerary->vehicle->model }}</div>
                                                            <div class="text-gray-500">Plate: {{ $ticket->itinerary->vehicle->plate_number }}</div>
                                                        @else
                                                            <span class="text-gray-500">No vehicle</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary)
                                                            {{ $ticket->itinerary->destination }}
                                                        @else
                                                            <span class="text-gray-500">No destination</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary)
                                                            {{ $ticket->itinerary->date_from ?? 'N/A' }} to {{ $ticket->itinerary->date_to ?? 'N/A' }}
                                                        @else
                                                            <span class="text-gray-500">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-900">
                                                        @if($ticket->itinerary)
                                                            <div>{{ Str::limit($ticket->itinerary->purpose, 50) }}</div>
                                                                                                                        @if($ticket->passengers && count($ticket->passengers) > 0)
                                                                                                                            <div class="text-xs text-gray-500 mt-1">
                                                                                                                                Passengers: {{ count($ticket->passengers) }}
                                                                                                                                @if($ticket->head_of_party)
                                                                                                                                    | Head: {{ Str::limit($ticket->head_of_party, 15) }}
                                                                                                                                @endif
                                                                                                                            </div>
                                                                                                                        @endif
                                                        @else
                                                            <span class="text-gray-500">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('trip-tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                                        <a href="{{ route('trip-tickets.edit', $ticket) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                                        <a href="#" class="text-green-600 hover:text-green-900" onclick="startTrip({{ $ticket->id }})">Start Trip</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="mt-4">
                                        {{ $pendingTripTickets->links() }}
                                    </div>
                                @else
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No pending trip tickets</h3>
                                        <p class="mt-1 text-sm text-gray-500">There are currently no pending trip tickets.</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Ongoing Tab Content -->
                            <div id="ongoing-tab" class="tab-content hidden">
                                @if($ongoingTripTickets->count() > 0)
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Head of Party</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($ongoingTripTickets as $ticket)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->id }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->head_of_party ?? 'Not set' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary && $ticket->itinerary->driver)
                                                            {{ $ticket->itinerary->driver->full_name }}
                                                        @else
                                                            <span class="text-gray-500">No driver</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary && $ticket->itinerary->vehicle)
                                                            <div class="font-medium">{{ $ticket->itinerary->vehicle->make }} {{ $ticket->itinerary->vehicle->model }}</div>
                                                            <div class="text-gray-500">Plate: {{ $ticket->itinerary->vehicle->plate_number }}</div>
                                                        @else
                                                            <span class="text-gray-500">No vehicle</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary)
                                                            {{ $ticket->itinerary->destination }}
                                                        @else
                                                            <span class="text-gray-500">No destination</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary)
                                                            {{ $ticket->itinerary->date_from ?? 'N/A' }} to {{ $ticket->itinerary->date_to ?? 'N/A' }}
                                                        @else
                                                            <span class="text-gray-500">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-900">
                                                        @if($ticket->itinerary)
                                                            <div>{{ Str::limit($ticket->itinerary->purpose, 50) }}</div>
                                                                                                                        @if($ticket->passengers && count($ticket->passengers) > 0)
                                                                                                                            <div class="text-xs text-gray-500 mt-1">
                                                                                                                                Passengers: {{ count($ticket->passengers) }}
                                                                                                                                @if($ticket->head_of_party)
                                                                                                                                    | Head: {{ Str::limit($ticket->head_of_party, 15) }}
                                                                                                                                @endif
                                                                                                                            </div>
                                                                                                                        @endif
                                                        @else
                                                            <span class="text-gray-500">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('trip-tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                                        <a href="{{ route('trip-tickets.edit', $ticket) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                                        <a href="#" class="text-green-600 hover:text-green-900" onclick="endTrip({{ $ticket->id }})">End Trip</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="mt-4">
                                        {{ $ongoingTripTickets->links() }}
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
                            
                            <!-- Completed Tab Content -->
                            <div id="completed-tab" class="tab-content hidden">
                                @if($completedTripTickets->count() > 0)
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Head of Party</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($completedTripTickets as $ticket)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->id }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->head_of_party ?? 'Not set' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary && $ticket->itinerary->driver)
                                                            {{ $ticket->itinerary->driver->full_name }}
                                                        @else
                                                            <span class="text-gray-500">No driver</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary && $ticket->itinerary->vehicle)
                                                            <div class="font-medium">{{ $ticket->itinerary->vehicle->make }} {{ $ticket->itinerary->vehicle->model }}</div>
                                                            <div class="text-gray-500">Plate: {{ $ticket->itinerary->vehicle->plate_number }}</div>
                                                        @else
                                                            <span class="text-gray-500">No vehicle</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary)
                                                            {{ $ticket->itinerary->destination }}
                                                        @else
                                                            <span class="text-gray-500">No destination</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary)
                                                            {{ $ticket->itinerary->date_from ?? 'N/A' }} to {{ $ticket->itinerary->date_to ?? 'N/A' }}
                                                        @else
                                                            <span class="text-gray-500">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-900">
                                                        @if($ticket->itinerary)
                                                            <div>{{ Str::limit($ticket->itinerary->purpose, 50) }}</div>
                                                                                                                        @if($ticket->passengers && count($ticket->passengers) > 0)
                                                                                                                            <div class="text-xs text-gray-500 mt-1">
                                                                                                                                Passengers: {{ count($ticket->passengers) }}
                                                                                                                                @if($ticket->head_of_party)
                                                                                                                                    | Head: {{ Str::limit($ticket->head_of_party, 15) }}
                                                                                                                                @endif
                                                                                                                            </div>
                                                                                                                        @endif
                                                        @else
                                                            <span class="text-gray-500">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('trip-tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                                        <a href="#" class="text-gray-600 hover:text-gray-900" onclick="archiveTrip({{ $ticket->id }})">Archive</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="mt-4">
                                        {{ $completedTripTickets->links() }}
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

                    <!-- Add JavaScript for tabs and actions -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Tab functionality
                            const tabButtons = document.querySelectorAll('.tab-button');
                            const tabContents = document.querySelectorAll('.tab-content');
                            
                            // Set first tab as active by default
                            if(tabContents.length > 0) {
                                document.getElementById('pending-tab').classList.remove('hidden');
                            }
                            
                            tabButtons.forEach(button => {
                                button.addEventListener('click', function() {
                                    const tabName = this.getAttribute('data-tab');
                                    
                                    // Remove active class from all buttons
                                    tabButtons.forEach(btn => {
                                        btn.classList.remove('active', 'bg-[#1e6031]', 'text-white', 'border-[#1e6031]');
                                        btn.classList.add('text-gray-500', 'border-transparent');
                                    });
                                    
                                    // Add active class to clicked button
                                    this.classList.add('active', 'bg-[#1e6031]', 'text-white', 'border-[#1e6031]');
                                    this.classList.remove('text-gray-500', 'border-transparent');
                                    
                                    // Hide all tab contents
                                    tabContents.forEach(content => {
                                        content.classList.add('hidden');
                                    });
                                    
                                    // Show selected tab content
                                    document.getElementById(tabName + '-tab').classList.remove('hidden');
                                });
                            });
                        });
                        
                        // Function to start a trip
                        function startTrip(ticketId) {
                            if(confirm('Are you sure you want to start this trip?')) {
                                // Create a form to submit the status update
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = `/trip-tickets/${ticketId}/status`;
                                
                                // Add CSRF token
                                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                const tokenInput = document.createElement('input');
                                tokenInput.type = 'hidden';
                                tokenInput.name = '_token';
                                tokenInput.value = csrfToken;
                                form.appendChild(tokenInput);
                                
                                // Add method spoofing for PATCH
                                const methodInput = document.createElement('input');
                                methodInput.type = 'hidden';
                                methodInput.name = '_method';
                                methodInput.value = 'PATCH';
                                form.appendChild(methodInput);
                                
                                // Add status field
                                const statusInput = document.createElement('input');
                                statusInput.type = 'hidden';
                                statusInput.name = 'status';
                                statusInput.value = 'Issued';
                                form.appendChild(statusInput);
                                
                                document.body.appendChild(form);
                                form.submit();
                            }
                        }
                        
                        // Function to end a trip
                        function endTrip(ticketId) {
                            if(confirm('Are you sure you want to end this trip?')) {
                                // Create a form to submit the status update
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = `/trip-tickets/${ticketId}/status`;
                                
                                // Add CSRF token
                                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                const tokenInput = document.createElement('input');
                                tokenInput.type = 'hidden';
                                tokenInput.name = '_token';
                                tokenInput.value = csrfToken;
                                form.appendChild(tokenInput);
                                
                                // Add method spoofing for PATCH
                                const methodInput = document.createElement('input');
                                methodInput.type = 'hidden';
                                methodInput.name = '_method';
                                methodInput.value = 'PATCH';
                                form.appendChild(methodInput);
                                
                                // Add status field
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
                                // Create a form to submit the status update
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = `/trip-tickets/${ticketId}/status`;
                                
                                // Add CSRF token
                                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                const tokenInput = document.createElement('input');
                                tokenInput.type = 'hidden';
                                tokenInput.name = '_token';
                                tokenInput.value = csrfToken;
                                form.appendChild(tokenInput);
                                
                                // Add method spoofing for PATCH
                                const methodInput = document.createElement('input');
                                methodInput.type = 'hidden';
                                methodInput.name = '_method';
                                methodInput.value = 'PATCH';
                                form.appendChild(methodInput);
                                
                                // Add status field
                                const statusInput = document.createElement('input');
                                statusInput.type = 'hidden';
                                statusInput.name = 'status';
                                statusInput.value = 'Archived';
                                form.appendChild(statusInput);
                                
                                document.body.appendChild(form);
                                form.submit();
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection