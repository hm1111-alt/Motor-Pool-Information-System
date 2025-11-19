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
                        
                        <!-- Search Box -->
                        <div class="mb-6">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" id="leadership-search" placeholder="Search offices, divisions, units..." 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm">
                            </div>
                        </div>
                        
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
                                            @if(isset($president))
                                            <div class="text-sm font-medium text-gray-900">{{ $president->first_name }} {{ $president->last_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $president->position_name }}</div>
                                            @else
                                            <div class="text-sm font-medium text-gray-900">No President Assigned</div>
                                            <div class="text-sm text-gray-500">University President Role</div>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.leaders.edit', ['type' => 'president']) }}" class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                        {{ isset($president) ? 'Change' : 'Assign' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vice Presidents by Office -->
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Vice Presidents by Office</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="offices-container">
                                @foreach($offices as $office)
                                    @php
                                        // Skip the University President's office for VP assignments
                                        $isPresidentOffice = strpos($office->office_name, 'Office of the University President') !== false;
                                        if ($isPresidentOffice) {
                                            continue;
                                        }
                                        $vp = $office->employees->where('is_vp', true)->first();
                                    @endphp
                                    <div class="bg-gray-50 rounded-lg p-4 office-item" data-name="{{ strtolower($office->office_name) }}">
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
                                                    <div class="text-sm font-medium text-gray-900">{{ $office->office_name }}</div>
                                                    @if($vp)
                                                        <div class="text-sm text-gray-500">{{ $vp->first_name }} {{ $vp->last_name }} - {{ $vp->position_name }}</div>
                                                    @else
                                                        <div class="text-sm text-gray-500">No VP Assigned</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <a href="{{ route('admin.leaders.edit', ['type' => 'vp', 'id' => $office->id]) }}" class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                                {{ $vp ? 'Change' : 'Assign' }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Division Heads by Division -->
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Division Heads by Division</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="divisions-container">
                                @foreach($divisions as $division)
                                    @php
                                        $divisionHead = $division->employees->where('is_divisionhead', true)->first();
                                    @endphp
                                    <div class="bg-gray-50 rounded-lg p-4 division-item" data-name="{{ strtolower($division->division_name) }}" data-office="{{ strtolower($division->office->office_name ?? '') }}">
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
                                                    <div class="text-sm font-medium text-gray-900">{{ $division->division_name }}</div>
                                                    @if($divisionHead)
                                                        <div class="text-sm text-gray-500">{{ $divisionHead->first_name }} {{ $divisionHead->last_name }} - {{ $divisionHead->position_name }}</div>
                                                    @else
                                                        <div class="text-sm text-gray-500">No Division Head Assigned</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <a href="{{ route('admin.leaders.edit', ['type' => 'division_head', 'id' => $division->id]) }}" class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                                {{ $divisionHead ? 'Change' : 'Assign' }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Unit Heads by Unit -->
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Unit Heads by Unit</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="units-container">
                                @foreach($units as $unit)
                                    @php
                                        $unitHead = $unit->employees->where('is_head', true)->first();
                                    @endphp
                                    <div class="bg-gray-50 rounded-lg p-4 unit-item" data-name="{{ strtolower($unit->unit_name) }}" data-division="{{ strtolower($unit->division->division_name ?? '') }}" data-office="{{ strtolower($unit->division->office->office_name ?? '') }}">
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
                                                    <div class="text-sm font-medium text-gray-900">{{ $unit->unit_name }}</div>
                                                    @if($unitHead)
                                                        <div class="text-sm text-gray-500">{{ $unitHead->first_name }} {{ $unitHead->last_name }} - {{ $unitHead->position_name }}</div>
                                                    @else
                                                        <div class="text-sm text-gray-500">No Unit Head Assigned</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <a href="{{ route('admin.leaders.edit', ['type' => 'unit_head', 'id' => $unit->id]) }}" class="text-[#1e6031] hover:text-[#164f2a] font-medium">
                                                {{ $unitHead ? 'Change' : 'Assign' }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript for live search -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('leadership-search');
            const officeItems = document.querySelectorAll('.office-item');
            const divisionItems = document.querySelectorAll('.division-item');
            const unitItems = document.querySelectorAll('.unit-item');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                // Filter offices
                officeItems.forEach(item => {
                    const name = item.getAttribute('data-name');
                    if (searchTerm === '' || name.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Filter divisions
                divisionItems.forEach(item => {
                    const name = item.getAttribute('data-name');
                    const office = item.getAttribute('data-office');
                    if (searchTerm === '' || name.includes(searchTerm) || office.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Filter units
                unitItems.forEach(item => {
                    const name = item.getAttribute('data-name');
                    const division = item.getAttribute('data-division');
                    const office = item.getAttribute('data-office');
                    if (searchTerm === '' || name.includes(searchTerm) || division.includes(searchTerm) || office.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-app-layout>