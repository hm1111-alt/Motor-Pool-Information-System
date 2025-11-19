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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- My Assignments Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#e0a70d] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">My Assignments</h3>
                                    <p class="text-gray-600">View current vehicle assignments</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#1e6031] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Trip Logs Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#ffd700]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#1e6031] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Trip Logs</h3>
                                    <p class="text-gray-600">Record and view trip details</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#1e6031] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Vehicle Status Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#ffd700] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Vehicle Status</h3>
                                    <p class="text-gray-600">Check vehicle condition and reports</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#1e6031] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>