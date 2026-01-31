<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Trip Tickets') }}
            </h2>
            <a href="{{ route('driver.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(!$driver)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Access Denied</h3>
                        <p class="mt-2 text-base text-gray-500">
                            No driver record found for your account.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('driver.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#1e6031] hover:bg-[#164f2a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1e6031]">
                                Return to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Search and Filter Section -->
                        <div class="mb-6 flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="search" 
                                           id="search"
                                           value="{{ $search }}"
                                           placeholder="Search by ticket number or purpose..."
                                           class="focus:ring-[#1e6031] focus:border-[#1e6031] block w-full pl-10 pr-12 py-2 sm:text-sm border-gray-300 rounded-lg">
                                    @if($search)
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <a href="{{ route('driver.trip-tickets') }}" class="text-gray-400 hover:text-gray-600">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <select name="status" id="status-filter" 
                                        class="focus:ring-[#1e6031] focus:border-[#1e6031] block w-full pl-3 pr-10 py-2 text-base border-gray-300 rounded-lg">
                                    <option value="">All Statuses</option>
                                    <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ $status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Rejected" {{ $status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </div>

                        <!-- Trip Tickets Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($tripTickets as $ticket)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $ticket->ticket_number }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $ticket->itinerary->purpose ?? 'N/A' }}</div>
                                                @if($ticket->itinerary->travelOrder)
                                                    <div class="text-sm text-gray-500">TO: {{ $ticket->itinerary->travelOrder->purpose ?? 'N/A' }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $ticket->itinerary->date?->format('M d, Y') ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($ticket->itinerary->vehicle)
                                                    {{ $ticket->itinerary->vehicle->make }} {{ $ticket->itinerary->vehicle->model }}
                                                    <div class="text-xs text-gray-400">Plate: {{ $ticket->itinerary->vehicle->plate_number }}</div>
                                                @else
                                                    <span class="text-gray-400">No vehicle assigned</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($ticket->status == 'Approved') bg-green-100 text-green-800
                                                    @elseif($ticket->status == 'Pending') bg-yellow-100 text-yellow-800
                                                    @elseif($ticket->status == 'Rejected') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $ticket->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No trip tickets found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $tripTickets->appends(['search' => $search, 'status' => $status])->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    @if($driver)
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle search input
        const searchInput = document.getElementById('search');
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                updateUrl();
            }, 500);
        });
        
        // Handle status filter
        const statusFilter = document.getElementById('status-filter');
        statusFilter.addEventListener('change', function() {
            updateUrl();
        });
        
        function updateUrl() {
            const search = searchInput.value;
            const status = statusFilter.value;
            let url = new URL('{{ route("driver.trip-tickets") }}', window.location.origin);
            
            if (search) {
                url.searchParams.set('search', search);
            }
            if (status) {
                url.searchParams.set('status', status);
            }
            
            window.location.href = url.toString();
        }
    });
    </script>
    @endif
</x-app-layout>