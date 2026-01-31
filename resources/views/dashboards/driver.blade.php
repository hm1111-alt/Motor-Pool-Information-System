<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Driver Dashboard') }}
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
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-6 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                {{ session('error') }}
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Please contact your system administrator to set up your driver account.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($driver)
                <!-- Welcome Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-[#1e6031] rounded-full p-3">
                                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-800">Welcome, {{ Auth::user()->name }}!</h3>
                                <p class="text-gray-600">Driver ID: {{ $driver->id }} | Status: 
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($driver->availability_status == 'Available') bg-green-100 text-green-800
                                        @elseif($driver->availability_status == 'Not Available') bg-red-100 text-red-800
                                        @elseif($driver->availability_status == 'On Duty') bg-blue-100 text-blue-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $driver->availability_status }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                        <div class="flex items-center">
                            <div class="rounded-full bg-[#e0a70d] p-3 mr-4">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">{{ $itineraries->total() }}</h3>
                                <p class="text-gray-600">Total Assignments</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#ffd700]">
                        <div class="flex items-center">
                            <div class="rounded-full bg-[#1e6031] p-3 mr-4">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">{{ $tripTickets->total() }}</h3>
                                <p class="text-gray-600">Trip Tickets</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                        <div class="flex items-center">
                            <div class="rounded-full bg-[#ffd700] p-3 mr-4">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">{{ $driver->position }}</h3>
                                <p class="text-gray-600">{{ $driver->official_station }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- My Assignments Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">My Assignments</h3>
                                <a href="{{ route('driver.itineraries') }}" class="text-[#1e6031] hover:text-[#164f2a] font-medium text-sm">
                                    View All →
                                </a>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($itineraries->count() > 0)
                                <div class="space-y-4">
                                    @foreach($itineraries->take(5) as $itinerary)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $itinerary->purpose ?? 'Unnamed Trip' }}</h4>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        {{ $itinerary->date?->format('M d, Y') ?? 'Date not set' }}
                                                    </p>
                                                    @if($itinerary->vehicle)
                                                        <p class="text-sm text-gray-500">
                                                            Vehicle: {{ $itinerary->vehicle->make }} {{ $itinerary->vehicle->model }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($itinerary->status == 'Approved') bg-green-100 text-green-800
                                                    @elseif($itinerary->status == 'Pending') bg-yellow-100 text-yellow-800
                                                    @elseif($itinerary->status == 'Rejected') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $itinerary->status }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No assignments yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">You don't have any itinerary assignments.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Trip Tickets Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">Trip Tickets</h3>
                                <a href="{{ route('driver.trip-tickets') }}" class="text-[#1e6031] hover:text-[#164f2a] font-medium text-sm">
                                    View All →
                                </a>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($tripTickets->count() > 0)
                                <div class="space-y-4">
                                    @foreach($tripTickets->take(5) as $ticket)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-medium text-gray-900">Ticket #{{ $ticket->ticket_number }}</h4>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        {{ $ticket->itinerary->purpose ?? 'Unnamed Trip' }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $ticket->itinerary->date?->format('M d, Y') ?? 'Date not set' }}
                                                    </p>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($ticket->status == 'Approved') bg-green-100 text-green-800
                                                    @elseif($ticket->status == 'Pending') bg-yellow-100 text-yellow-800
                                                    @elseif($ticket->status == 'Rejected') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $ticket->status }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No trip tickets</h3>
                                    <p class="mt-1 text-sm text-gray-500">You don't have any trip tickets yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Calendar Section -->
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">My Schedule</h3>
                        <p class="text-sm text-gray-600 mt-1">Upcoming trips in the next 30 days</p>
                    </div>
                    <div class="p-6">
                        @if(count($calendarEvents) > 0)
                            <div id="driver-calendar" class="h-96"></div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No upcoming trips</h3>
                                <p class="mt-1 text-sm text-gray-500">You don't have any scheduled trips in the next 30 days.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- No Driver Record Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Driver Account Not Found</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Your user account is not linked to a driver record in the system.
                        </p>
                        <div class="mt-6">
                            <p class="text-sm text-gray-500">
                                Please contact your system administrator to set up your driver account.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($driver && count($calendarEvents) > 0)
    <!-- FullCalendar CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('driver-calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: @json($calendarEvents),
            eventContent: function(arg) {
                return {
                    html: '<div class="p-1 text-xs">' + 
                          '<div class="font-medium">' + arg.event.title + '</div>' +
                          '<div class="text-xs opacity-75">' + arg.event.extendedProps.vehicle + '</div>' +
                          '</div>'
                };
            },
            eventDidMount: function(info) {
                info.el.classList.add('cursor-pointer');
                info.el.title = info.event.title + '\n' + 
                               'Vehicle: ' + info.event.extendedProps.vehicle + '\n' +
                               'Status: ' + info.event.extendedProps.status + '\n' +
                               'Destination: ' + info.event.extendedProps.destination;
            }
        });
        calendar.render();
    });
    </script>
    @endif
</x-app-layout>