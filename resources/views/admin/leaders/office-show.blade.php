<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('admin.leaders.offices') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-500 border border-gray-600 rounded-md text-white hover:bg-gray-600 hover:border-gray-700 transition-all duration-200 no-underline text-sm" style="text-decoration: none !important;">
                    <svg class="h-4 w-4 mr-1.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
          <!-- VP Section -->
<div class="mb-8"> <!-- removed bg-white shadow-sm sm:rounded-lg -->
    
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-2xl font-bold text-green-600 leading-snug m-0">Vice President</h3>
            <p class="text-gray-600 text-sm leading-snug m-0">Office-level leadership</p>
        </div>

        @if($vp)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Assigned
            </span>
        @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Unassigned
            </span>
        @endif
    </div>


                    
    <!-- VP Content - Overview Style -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 shadow-sm">
        <div class="px-4 py-3">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-start">
                    <!-- VP Icon -->
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg p-2 mr-3 flex-shrink-0 mt-1">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    
                    <!-- VP Details -->
                    <div>
                        @if($vp)
                            <div class="text-lg font-semibold text-gray-800 mb-0">
                                {{ $vp->first_name }} {{ $vp->last_name }}
                            </div>
                            <div class="text-gray-600 text-sm mt-0">
                                {{ $vp->position_name }}
                            </div>
                            <div class="mt-1 flex items-center text-sm text-gray-600">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Office Leadership Role
                            </div>
                        @else
                            <div class="text-lg font-semibold text-gray-400 mb-0">No Vice President Assigned</div>
                            <div class="text-gray-500 text-sm mt-0">Office Leadership Role</div>
                            <div class="mt-1 flex items-center text-sm text-gray-400">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Requires Assignment
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Action Button - Moved to the right side -->
                <div class="flex-shrink-0 self-start">
                    @php
                        $isPresidentOffice = strpos($office->office_name, 'Office of the University President') !== false;
                    @endphp
                    @if(!$isPresidentOffice)
                        <a href="{{ route('admin.leaders.edit', ['type' => 'vp', 'id' => $office->id]) }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white {{ $vp ? 'bg-green-600 hover:bg-green-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 no-underline" style="text-decoration: none !important;">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ $vp ? 'Change VP' : 'Assign VP' }}
                        </a>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-500 bg-gray-100 rounded-md">
                            President's Office - No VP Assignment
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    </div>

       <!-- Divisions Section -->
            <div class="mt-10 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-green-600 leading-snug m-0">Divisions</h3>
                        <p class="text-gray-600 text-sm leading-snug m-0">Division-level leadership under this office</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $office->divisions->count() }} Divisions
                        </span>
                    </div>
                </div>

                    @if($office->divisions->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No divisions found</h3>
                            <p class="mt-1 text-sm text-gray-500">Create divisions in the organization management section.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($office->divisions as $division)
                                @php
                                    $divisionHead = $division->employees->filter(function ($employee) {
                                        return $employee->is_divisionhead;
                                    })->first();
                                @endphp
                                
                                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border border-indigo-400 p-4 transition-all duration-300 hover:shadow-lg hover:scale-105">
                                    <div class="p-0">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 w-10 h-10 flex items-center justify-center shadow-md">
                                                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <h4 class="text-sm font-semibold text-gray-800">{{ $division->division_name }}</h4>
                                                    <div class="text-xs text-gray-600 mt-1 flex flex-wrap gap-2">
                                                        <span class="inline-flex items-center">
                                                            <svg class="h-3 w-3 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                                            </svg>
                                                            {{ $division->units->count() }} Units
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if($divisionHead)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Assigned
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    Unassigned
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="border-t border-gray-100 pt-3">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    @if($divisionHead)
                                                        <div class="text-sm font-medium text-gray-800">{{ $divisionHead->first_name }} {{ $divisionHead->last_name }}</div>
                                                        <div class="text-xs text-gray-600">{{ $divisionHead->position_name }}</div>
                                                    @else
                                                        <div class="text-sm font-medium text-gray-400">No Division Head</div>
                                                        <div class="text-xs text-gray-400">Division Leadership Role</div>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.leaders.division.show', $division) }}" 
                                                       class="inline-flex items-center px-2.5 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 no-underline" style="text-decoration: none !important;">
                                                        <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        View
                                                    </a>
                                                    
                                                    <a href="{{ route('admin.leaders.edit', ['type' => 'division_head', 'id' => $division->id]) }}" 
                                                       class="inline-flex items-center px-2.5 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white {{ $divisionHead ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 no-underline" style="text-decoration: none !important;">
                                                        <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        {{ $divisionHead ? 'Change' : 'Assign' }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
            </div>
        </div>
    </div>
</x-admin-layout>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
