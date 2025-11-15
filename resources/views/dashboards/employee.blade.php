<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if($user->employee)
                Welcome, {{ $user->employee->first_name }}!
            @else
                Welcome, {{ $user->name }}!
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Profile Information Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#009639]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#e0a70d] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Profile Information</h3>
                                    <p class="text-gray-600">View and update your profile</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('profile.edit') }}" class="text-[#009639] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Vehicle Reservations Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#ffd700]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#009639] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Vehicle Reservations</h3>
                                    <p class="text-gray-600">Request and manage vehicle bookings</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#009639] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- My Requests Card (renamed from Create Travel Orders) -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#ffd700] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">My Requests</h3>
                                    <p class="text-gray-600">View status of submitted requests</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('travel-orders.index') }}" class="text-[#009639] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Create Travel Order Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#e0a70d]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#1e6031] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Create Travel Order</h3>
                                    <p class="text-gray-600">Submit a new travel request</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('travel-orders.create') }}" class="text-[#009639] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Vehicle Availability Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#009639]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#ffd700] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Vehicle Availability</h3>
                                    <p class="text-gray-600">Check current vehicle availability</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#009639] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Travel History Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#e0a70d] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Travel History</h3>
                                    <p class="text-gray-600">View your travel records</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#009639] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>