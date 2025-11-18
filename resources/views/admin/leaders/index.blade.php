<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leadership Roles Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Leadership Roles Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Assign Leadership Roles</h3>
                        <p class="text-gray-600 mb-6">Assign and manage leadership positions within the organization.</p>
                        
                        <!-- President -->
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-800 mb-3">University President</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Dr. Maria Elena B. Dimayuga</div>
                                            <div class="text-sm text-gray-500">President</div>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.leaders.edit', ['type' => 'president']) }}" class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                        Change
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vice Presidents -->
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Vice Presidents</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if(isset($vps) && $vps->count() > 0)
                                    @foreach($vps as $vp)
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-12 w-12">
                                                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $vp->first_name }} {{ $vp->last_name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $vp->position_name }}</div>
                                                    </div>
                                                </div>
                                                <a href="{{ route('admin.leaders.edit', ['type' => 'vp', 'id' => $vp->id]) }}" class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                                    Change
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12">
                                                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">No VP Assigned</div>
                                                    <div class="text-sm text-gray-500">Vice President Role</div>
                                                </div>
                                            </div>
                                            <a href="{{ route('admin.leaders.edit', ['type' => 'vp']) }}" class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                                Assign
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Division Heads -->
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Division Heads</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @if(isset($divisionHeads) && $divisionHeads->count() > 0)
                                    @foreach($divisionHeads as $divisionHead)
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-12 w-12">
                                                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $divisionHead->first_name }} {{ $divisionHead->last_name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $divisionHead->division->division_name ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                                <a href="{{ route('admin.leaders.edit', ['type' => 'division_head', 'id' => $divisionHead->id]) }}" class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                                    Change
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12">
                                                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">No Division Head Assigned</div>
                                                    <div class="text-sm text-gray-500">Division Head Role</div>
                                                </div>
                                            </div>
                                            <a href="{{ route('admin.leaders.edit', ['type' => 'division_head']) }}" class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                                Assign
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Unit Heads -->
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Unit Heads</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Robert B. Cruz</div>
                                                <div class="text-sm text-gray-500">Planning and Development Office</div>
                                            </div>
                                        </div>
                                        <button class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                            Change
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Patricia L. Reyes</div>
                                                <div class="text-sm text-gray-500">Alumni Relations Office</div>
                                            </div>
                                        </div>
                                        <button class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                            Change
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Subunit Heads -->
                        <div>
                            <h4 class="text-md font-medium text-gray-800 mb-3">Subunit Heads</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Michael T. Santos</div>
                                                <div class="text-sm text-gray-500">University Science High School</div>
                                            </div>
                                        </div>
                                        <button class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                            Change
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Jennifer A. Lim</div>
                                                <div class="text-sm text-gray-500">Agricultural Science and Technology School</div>
                                            </div>
                                        </div>
                                        <button class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                            Change
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>