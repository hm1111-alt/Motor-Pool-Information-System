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
                            Motorpool Admin
                        </div>
                        <button @click="sidebarOpen = !sidebarOpen; $dispatch('sidebarToggled')" @sidebarToggled.window="setTimeout(() => { if (window.dispatchEvent) { window.dispatchEvent(new Event('resize')); } }, 350)" class="text-white hover:text-gray-200 focus:outline-none">
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
                                    @if(auth()->user())
                                        {{ auth()->user()->name }}
                                    @else
                                        Motorpool Admin
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
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <div class="rounded-lg bg-[#1e6031] p-2 mr-3 flex-shrink-0">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium truncate">Dashboard</span>
                        </a>
                        
                        <!-- Trip Tickets -->
                        <a href="{{ route('trip-tickets.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <div class="rounded-lg bg-[#1e6031] p-2 mr-3 flex-shrink-0">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium truncate">Trip Tickets</span>
                        </a>

                        <!-- Vehicles -->
                        <a href="{{ route('vehicles.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <div class="rounded-lg bg-[#1e6031] p-2 mr-3 flex-shrink-0">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium truncate">Vehicles</span>
                        </a>
                        
                        <!-- Add Vehicle - Placed under Vehicles -->
                        <a href="{{ route('vehicles.create') }}" class="flex items-center px-4 py-2 bg-green-600 text-white hover:bg-green-700 rounded-lg transition-colors duration-200 mb-1 ml-4">
                            <div class="rounded-lg bg-white p-1 mr-2 flex-shrink-0">
                                <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium truncate text-sm">Add Vehicle</span>
                        </a>

                        <!-- Drivers -->
                        <a href="{{ route('drivers.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <div class="rounded-lg bg-[#1e6031] p-2 mr-3 flex-shrink-0">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium truncate">Drivers</span>
                        </a>
                        
                        <!-- Add Driver - Placed under Drivers -->
                        <a href="{{ route('drivers.create') }}" class="flex items-center px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition-colors duration-200 mb-1 ml-4">
                            <div class="rounded-lg bg-white p-1 mr-2 flex-shrink-0">
                                <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium truncate text-sm">Add Driver</span>
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