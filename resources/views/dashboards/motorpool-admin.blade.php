@extends('layouts.motorpool-admin')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Motorpool Admin Dashboard') }}
        </h2>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Vehicle Management Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#e0a70d] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Vehicle Management</h3>
                                    <p class="text-gray-600">Manage fleet vehicles</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('vehicles.index') }}" class="text-[#1e6031] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Driver Management Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#ffd700]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#1e6031] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Driver Management</h3>
                                    <p class="text-gray-600">Manage drivers and assignments</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#1e6031] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Reports Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#ffd700] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Reports & Analytics</h3>
                                    <p class="text-gray-600">View fleet performance reports</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#1e6031] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Maintenance Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#e0a70d]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#1e6031] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Maintenance</h3>
                                    <p class="text-gray-600">Schedule and track maintenance</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#1e6031] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Reservations Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#ffd700] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Reservations</h3>
                                    <p class="text-gray-600">Manage vehicle reservations</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#1e6031] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Settings Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#e0a70d] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">System Settings</h3>
                                    <p class="text-gray-600">Configure system parameters</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#1e6031] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Trip Tickets Section -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Trip Tickets</h3>
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                            @if(isset($tripTickets) && $tripTickets->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Itinerary</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Head of Party</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($tripTickets as $ticket)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->id }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary)
                                                            {{ $ticket->itinerary->id }} - {{ $ticket->itinerary->destination }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary && $ticket->itinerary->driver)
                                                            {{ $ticket->itinerary->driver->name }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($ticket->itinerary && $ticket->itinerary->vehicle)
                                                            {{ $ticket->itinerary->vehicle->make }} {{ $ticket->itinerary->vehicle->model }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $ticket->head_of_party ?? 'Not set' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            @if($ticket->status === 'Pending') bg-yellow-100 text-yellow-800
                                                            @elseif($ticket->status === 'Approved') bg-green-100 text-green-800
                                                            @elseif($ticket->status === 'Rejected') bg-red-100 text-red-800
                                                            @else bg-blue-100 text-blue-800
                                                            @endif">
                                                            {{ $ticket->status }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('trip-tickets.show', $ticket) }}" class="text-[#1e6031] hover:text-[#164f2a] mr-3">View</a>
                                                        <a href="{{ route('trip-tickets.edit', $ticket) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-600 mb-4">No trip tickets have been created yet.</p>
                            @endif
                            <div class="mt-4">
                                <a href="{{ route('trip-tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#1e6031] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Manage Trip Tickets
                                    <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Travel Orders Section -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Recently Approved Travel Orders</h3>
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                            <p class="text-gray-600 mb-4">View the most recently approved travel orders that require motorpool attention.</p>
                            <a href="{{ route('approved-travel-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#1e6031] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                View Approved Travel Orders
                                <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection