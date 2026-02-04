<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Leadership Management') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-gray-900">Organizational Leadership Hierarchy</h3>
                            <p class="text-gray-600 mt-2">Manage leadership roles across the organization structure</p>
                        </div>
                    </div>

                    <!-- Navigation Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- President Card -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-500 mb-4 mx-auto">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-center text-gray-900 mb-2">University President</h4>
                            <p class="text-center text-gray-600 text-sm mb-4">Top-level executive leadership</p>
                            
                            @if(isset($president))
                                <div class="text-center mb-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $president->first_name }} {{ $president->last_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $president->position_name }}</div>
                                </div>
                                <div class="text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Assigned
                                    </span>
                                </div>
                            @else
                                <div class="text-center mb-4">
                                    <div class="text-sm font-medium text-gray-400">No President Assigned</div>
                                    <div class="text-xs text-gray-400">University Leadership Role</div>
                                </div>
                                <div class="text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Unassigned
                                    </span>
                                </div>
                            @endif
                            
                            <div class="mt-4">
                                <a href="{{ route('admin.leaders.edit', ['type' => 'president']) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white {{ isset($president) ? 'bg-purple-600 hover:bg-purple-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ isset($president) ? 'Change President' : 'Assign President' }}
                                </a>
                            </div>
                        </div>

                        <!-- Offices Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-500 mb-4 mx-auto">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-center text-gray-900 mb-2">Offices</h4>
                            <p class="text-center text-gray-600 text-sm mb-4">Manage office-level leadership (Vice Presidents)</p>
                            
                            <div class="text-center mb-4">
                                <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Office::count() }}</div>
                                <div class="text-sm text-gray-500">Offices</div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('admin.leaders.offices') }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Manage Offices
                                </a>
                            </div>
                        </div>

                        <!-- Overview Card -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-500 mb-4 mx-auto">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-center text-gray-900 mb-2">Organization Overview</h4>
                            <p class="text-center text-gray-600 text-sm mb-4">View organizational structure</p>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Divisions:</span>
                                    <span class="font-medium text-gray-900">{{ \App\Models\Division::count() }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Units:</span>
                                    <span class="font-medium text-gray-900">{{ \App\Models\Unit::count() }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total Employees:</span>
                                    <span class="font-medium text-gray-900">{{ \App\Models\Employee::count() }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('admin.employees.index') }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    View Employees
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h4 class="text-sm font-medium text-green-800">Leadership Status Summary</h4>
                        </div>
                        <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-700">{{ \App\Models\Officer::where('president', true)->count() }}</div>
                                <div class="text-xs text-green-600">Presidents</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-700">{{ \App\Models\Officer::where('vp', true)->count() }}</div>
                                <div class="text-xs text-blue-600">VPs</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-700">{{ \App\Models\Officer::where('division_head', true)->count() }}</div>
                                <div class="text-xs text-indigo-600">Division Heads</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-teal-700">{{ \App\Models\Officer::where('unit_head', true)->count() }}</div>
                                <div class="text-xs text-teal-600">Unit Heads</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>