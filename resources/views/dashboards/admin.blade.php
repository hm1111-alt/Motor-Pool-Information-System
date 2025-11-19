<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('CLSU Organization Management') }}
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

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Welcome Section -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">
                            Welcome, {{ Auth::user()->name }}!
                        </h3>
                        <p class="text-gray-600">Manage the CLSU organizational structure and employee information</p>
                    </div>
                    
                    <!-- Organization Structure Management -->
                    <div class="mb-10">
                        <h4 class="text-xl font-semibold text-gray-800 mb-6 pb-2 border-b border-gray-200">Organization Structure</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Offices Management -->
                            <a href="{{ route('admin.offices.index') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                                <div class="p-6">
                                    <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Offices</h4>
                                    <p class="text-gray-600 text-sm mb-3">Manage university offices and their details</p>
                                    <div class="flex space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Create
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Edit
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Delete
                                        </span>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Divisions Management -->
                            <a href="{{ route('admin.divisions.index') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                                <div class="p-6">
                                    <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Divisions</h4>
                                    <p class="text-gray-600 text-sm mb-3">Manage divisions within offices</p>
                                    <div class="flex space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Create
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Edit
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Delete
                                        </span>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Units Management -->
                            <a href="{{ route('admin.units.index') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                                <div class="p-6">
                                    <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Units</h4>
                                    <p class="text-gray-600 text-sm mb-3">Manage units within divisions</p>
                                    <div class="flex space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Create
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Edit
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Delete
                                        </span>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Subunits Management -->
                            <a href="{{ route('admin.subunits.index') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                                <div class="p-6">
                                    <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Subunits</h4>
                                    <p class="text-gray-600 text-sm mb-3">Manage subunits within units</p>
                                    <div class="flex space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Create
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Edit
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Delete
                                        </span>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Classes Management -->
                            <a href="{{ route('admin.classes.index') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                                <div class="p-6">
                                    <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Classes</h4>
                                    <p class="text-gray-600 text-sm mb-3">Manage employee classes (Staff, Faculty)</p>
                                    <div class="flex space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Create
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Edit
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Delete
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Personnel Management -->
                    <div class="mb-10">
                        <h4 class="text-xl font-semibold text-gray-800 mb-6 pb-2 border-b border-gray-200">Personnel Management</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Employees Management -->
                            <a href="{{ route('admin.employees.index') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                                <div class="p-6">
                                    <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Employees</h4>
                                    <p class="text-gray-600 text-sm mb-3">Manage employee records and assignments</p>
                                    <div class="flex space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Create
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Edit
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Delete
                                        </span>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Leadership Roles -->
                            <a href="{{ route('admin.leaders.index') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 block">
                                <div class="p-6">
                                    <div class="rounded-lg bg-[#1e6031] p-3 w-12 h-12 flex items-center justify-center mb-4">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Leadership Roles</h4>
                                    <p class="text-gray-600 text-sm mb-3">Assign and manage leadership positions</p>
                                    <div class="flex space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Edit
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Stats Section -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Organization Overview</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white p-4 rounded-lg border border-gray-200 text-center">
                                <p class="text-sm text-gray-600">Total Offices</p>
                                <p class="text-2xl font-bold text-[#1e6031]">5</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200 text-center">
                                <p class="text-sm text-gray-600">Total Divisions</p>
                                <p class="text-2xl font-bold text-[#1e6031]">22</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200 text-center">
                                <p class="text-sm text-gray-600">Total Employees</p>
                                <p class="text-2xl font-bold text-[#1e6031]">450</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200 text-center">
                                <p class="text-sm text-gray-600">Active Users</p>
                                <p class="text-2xl font-bold text-[#1e6031]">128</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>