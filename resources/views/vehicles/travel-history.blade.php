@extends('layouts.motorpool-admin')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    VEHICLE TRAVEL HISTORY
                </h2>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filters -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search Filter -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="text" 
                                       name="search" 
                                       id="search"
                                       value="{{ $search }}"
                                       placeholder="Search by destination, driver, or head of party..."
                                       class="focus:ring-[#1e6031] focus:border-[#1e6031] block w-full pr-10 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vehicle Filter -->
                        <div>
                            <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Vehicle</label>
                            <select name="vehicle_id" id="vehicle_id" 
                                    class="focus:ring-[#1e6031] focus:border-[#1e6031] block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">All Vehicles</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ $vehicleId == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->plate_number }} - {{ $vehicle->model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Apply Filters Button -->
                        <div class="flex items-end">
                            <button type="button" id="apply-filters" 
                                    class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Apply Filters
                            </button>
                        </div>
                    </div>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4">
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

                    <!-- Travel History Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trip #</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Head of Party</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departure Date & Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrival Date & Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distance (km)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($tripTickets as $tripTicket)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ str_pad($tripTicket->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $tripTicket->itinerary->vehicle->plate_number ?? 'N/A' }}
                                            <div class="text-xs text-gray-400">{{ $tripTicket->itinerary->vehicle->model ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $tripTicket->itinerary->driver->first_name ?? '' }} 
                                            {{ $tripTicket->itinerary->driver->last_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $tripTicket->head_of_party }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $tripTicket->itinerary->destination ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($tripTicket->itinerary->date_from)
                                                {{ $tripTicket->itinerary->date_from->format('M d, Y') }}
                                                @if($tripTicket->itinerary->departure_time)
                                                    <br><span class="text-xs">{{ $tripTicket->itinerary->departure_time }}</span>
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($tripTicket->itinerary->date_to)
                                                {{ $tripTicket->itinerary->date_to->format('M d, Y') }}
                                                @if($tripTicket->itinerary->departure_time)
                                                    <br><span class="text-xs">{{ $tripTicket->itinerary->departure_time }}</span>
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $tripTicket->estimated_distance ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No completed trip tickets found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $tripTickets->appends(['search' => $search, 'vehicle_id' => $vehicleId])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const applyFiltersBtn = document.getElementById('apply-filters');
        const searchInput = document.getElementById('search');
        const vehicleSelect = document.getElementById('vehicle_id');
        
        function applyFilters() {
            const search = searchInput.value;
            const vehicleId = vehicleSelect.value;
            
            let url = new URL('{{ route("vehicles.travel-history") }}', window.location.origin);
            
            if (search) {
                url.searchParams.set('search', search);
            }
            
            if (vehicleId) {
                url.searchParams.set('vehicle_id', vehicleId);
            }
            
            window.location.href = url.toString();
        }
        
        applyFiltersBtn.addEventListener('click', applyFilters);
        
        // Also apply filters when pressing Enter in search box
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
        
        // Apply filters when vehicle selection changes
        vehicleSelect.addEventListener('change', applyFilters);
    });
    </script>
@endsection