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
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                toast: false,
                position: 'center',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                toast: false,
                position: 'center',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                timer: 7000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    });
</script>
<body class="font-libre-franklin antialiased h-full">
    <div class="flex h-screen bg-gray-50">
        <!-- Collapsible Sidebar -->
        <div x-data="{ sidebarOpen: true }" class="flex flex-shrink-0">
            <div :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white shadow-lg transition-all duration-300 ease-in-out flex flex-col h-full sticky top-0">
                
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between p-3 border-b border-gray-200 bg-[#1e6031]">
                    <div :class="sidebarOpen ? 'block' : 'hidden'" class="text-lg font-semibold text-white truncate">
                        Motorpool Admin
                    </div>
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg :class="sidebarOpen ? 'rotate-180' : ''" class="h-5 w-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <!-- User Info -->
                <div class="p-3 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-10 flex items-center justify-center flex-shrink-0">
                            <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div :class="sidebarOpen ? 'block ml-2' : 'hidden'" class="min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->name ?? 'Motorpool Admin' }}</p>
                            <a href="{{ route('profile.edit') }}" class="text-xs text-[#1e6031] hover:text-[#007d31] font-medium truncate block mt-0.5">
                                Manage Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu (scrollable) -->
                <div class="flex-1 flex flex-col overflow-y-auto">
                    <nav class="px-2 py-3 space-y-1 flex-1">
@foreach([
    ['route' => 'motorpool.dashboard', 'label' => 'Dashboard', 'icon' => 'fas fa-home'],
    ['route' => 'motorpool.travel-orders.index', 'label' => 'Travel Orders', 'icon' => 'fas fa-clipboard-check'],
    ['route' => 'itinerary.index', 'label' => 'Itinerary', 'icon' => 'fas fa-file-alt'],
    ['route' => 'trip-tickets.index', 'label' => 'Trip Tickets', 'icon' => 'fas fa-file-signature'],
    ['route' => 'vehicles.index', 'label' => 'Vehicles', 'icon' => 'fas fa-car'],
    ['route' => 'drivers.index', 'label' => 'Drivers', 'icon' => 'fas fa-id-badge'],
] as $item)
    <a href="{{ route($item['route']) }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
        <div class="rounded-lg bg-[#1e6031] p-2 mr-2 flex-shrink-0 flex items-center justify-center">
            <i class="{{ $item['icon'] }} text-white w-4 h-4"></i>
        </div>
        <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium text-sm truncate">{{ $item['label'] }}</span>
    </a>
@endforeach

                    </nav>

                    <!-- Logout always at bottom -->
                    <div class="px-3 py-2 border-t border-gray-200 mt-auto">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                                <div class="rounded-lg bg-[#1e6031] p-2 mr-2 flex-shrink-0">
                                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                                <span :class="sidebarOpen ? 'block' : 'hidden'" class="font-medium text-sm truncate">Logout</span>
                            </button>
                        </form>
                    </div>
                </div> <!-- end flex-1 flex flex-col -->
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @isset($header)
                <header class="bg-white shadow flex-shrink-0">
                    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8 text-sm">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
