<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=libre-franklin:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-libre-franklin antialiased h-full">
        <div class="flex min-h-screen bg-gray-50">
            <!-- Collapsible Sidebar -->
            <div x-data="{ sidebarOpen: true }" class="flex">
                <!-- Sidebar -->
                <div :class="sidebarOpen ? 'w-64 md:w-64' : 'w-20'" class="bg-white shadow-lg transition-all duration-300 ease-in-out flex flex-col h-screen sticky top-0 overflow-hidden">
                    <!-- Sidebar Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-[#1e6031]">
                        <div :class="sidebarOpen ? 'block' : 'hidden'" class="text-xl font-semibold text-white truncate">
                            Employee Panel
                        </div>
                        <button @click="sidebarOpen = !sidebarOpen" class="text-white hover:text-gray-200 focus:outline-none">
                            <svg :class="sidebarOpen ? 'rotate-180' : ''" class="h-6 w-6 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                    </div>
                    <!-- User Info -->
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div :class="sidebarOpen ? 'block ml-3' : 'hidden'" class="min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">
                                    @if(auth()->user()->employee)
                                        {{ auth()->user()->employee->first_name }} {{ auth()->user()->employee->last_name }}
                                    @else
                                        {{ auth()->user()->name }}
                                    @endif
                                </p>
                                <a href="{{ route('profile.edit') }}" class="text-xs text-[#1e6031] hover:text-[#007d31] font-medium truncate block mt-1">
                                    Manage Profile
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Navigation Menu -->
                    <nav class="flex-1 px-2 py-4 overflow-y-auto">
                        <!-- My Requests -->
                        <a href="{{ route('travel-orders.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <div class="rounded-lg bg-[#1e6031] p-2 mr-3 flex-shrink-0">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium truncate">My Requests</span>
                        </a>
                        
                        <!-- Create Travel Order -->
                        <a href="{{ route('travel-orders.create') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <div class="rounded-lg bg-[#1e6031] p-2 mr-3 flex-shrink-0">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium truncate">Create Travel Order</span>
                        </a>
                        
                        <!-- Vehicle Calendar -->
                        <a href="{{ route('vehicle-calendar.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <div class="rounded-lg bg-[#1e6031] p-2 mr-3 flex-shrink-0">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium truncate">Vehicle Calendar</span>
                        </a>
                        
                        <!-- Travel History -->
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <div class="rounded-lg bg-[#1e6031] p-2 mr-3 flex-shrink-0">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium truncate">Travel History</span>
                        </a>
                    </nav>
                    <!-- Logout Section -->
                    <div class="p-4 border-t border-gray-200 mt-auto">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                                <div class="rounded-lg bg-[#1e6031] p-2 mr-3 flex-shrink-0">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                                <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium truncate">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="flex-1 overflow-hidden">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="overflow-y-auto h-[calc(100vh-4rem)]">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>