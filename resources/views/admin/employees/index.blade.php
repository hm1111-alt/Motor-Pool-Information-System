<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <svg class="inline-block h-6 w-6 text-[#1e6031] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                {{ __('Employee Management') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.employees.create') }}" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Employee
                </a>
                <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search and Filter Section -->
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        id="employee-search"
                                        placeholder="Search employees..." 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50 px-4 py-2"
                                        value="{{ request('search', '') }}"
                                    >
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <select id="office-filter" class="rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <option value="all">All Offices</option>
                                    @foreach($offices as $office)
                                        <option value="{{ $office->id }}" {{ request('office', 'all') == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                    @endforeach
                                </select>
                                <select id="division-filter" class="rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <option value="all">All Divisions</option>
                                </select>
                                <select id="unit-filter" class="rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <option value="all">All Units</option>
                                </select>
                                <select id="subunit-filter" class="rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <option value="all">All Subunits</option>
                                </select>
                                <select id="class-filter" class="rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <option value="all">All Classes</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('class', 'all') == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                <select id="status-filter" class="rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="active" {{ request('status', 'all') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status', 'all') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Employees Table -->
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="min-w-full divide-y divide-gray-200" id="employees-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="employees-table-body">
                                @include('admin.employees.partials.table-body')
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination-section">
                        @include('admin.employees.partials.pagination')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Live search functionality with server-side filtering
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('employee-search');
            const officeFilter = document.getElementById('office-filter');
            const divisionFilter = document.getElementById('division-filter');
            const unitFilter = document.getElementById('unit-filter');
            const subunitFilter = document.getElementById('subunit-filter');
            const classFilter = document.getElementById('class-filter');
            const statusFilter = document.getElementById('status-filter');
            const tableBody = document.getElementById('employees-table-body');
            const paginationSection = document.getElementById('pagination-section');
            
            let searchTimeout;
            
            // Function to perform server-side search
            function performSearch() {
                const searchTerm = searchInput.value.trim();
                const officeTerm = officeFilter.value;
                const divisionTerm = divisionFilter.value;
                const unitTerm = unitFilter.value;
                const subunitTerm = subunitFilter.value;
                const classTerm = classFilter.value;
                const statusTerm = statusFilter.value;
                
                // Clear previous timeout to debounce requests
                clearTimeout(searchTimeout);
                
                // Set new timeout
                searchTimeout = setTimeout(() => {
                    // Show loading state
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center">
                                <div class="flex justify-center">
                                    <svg class="animate-spin h-5 w-5 text-[#1e6031]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </td>
                        </tr>
                    `;
                    
                    // Build URL with search parameters
                    let url = new URL(window.location.href);
                    url.searchParams.set('search', searchTerm);
                    url.searchParams.set('office', officeTerm);
                    url.searchParams.set('division', divisionTerm);
                    url.searchParams.set('unit', unitTerm);
                    url.searchParams.set('subunit', subunitTerm);
                    url.searchParams.set('class', classTerm);
                    url.searchParams.set('status', statusTerm);
                    
                    // If search is empty and filters are default, remove parameters
                    if (searchTerm === '' && officeTerm === 'all' && divisionTerm === 'all' && unitTerm === 'all' && subunitTerm === 'all' && classTerm === 'all' && statusTerm === 'all') {
                        url.searchParams.delete('search');
                        url.searchParams.delete('office');
                        url.searchParams.delete('division');
                        url.searchParams.delete('unit');
                        url.searchParams.delete('subunit');
                        url.searchParams.delete('class');
                        url.searchParams.delete('status');
                    } else if (searchTerm === '') {
                        url.searchParams.delete('search');
                    } else if (officeTerm === 'all') {
                        url.searchParams.delete('office');
                    } else if (divisionTerm === 'all') {
                        url.searchParams.delete('division');
                    } else if (unitTerm === 'all') {
                        url.searchParams.delete('unit');
                    } else if (subunitTerm === 'all') {
                        url.searchParams.delete('subunit');
                    } else if (classTerm === 'all') {
                        url.searchParams.delete('class');
                    } else if (statusTerm === 'all') {
                        url.searchParams.delete('status');
                    }
                    
                    // Fetch results
                    fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.table_body) {
                            tableBody.innerHTML = data.table_body;
                        }
                        
                        if (data.pagination && paginationSection) {
                            paginationSection.innerHTML = data.pagination;
                        }
                        
                        // Reattach event listeners
                        attachEventListeners();
                        
                        // Load dependent dropdowns
                        if (officeTerm !== 'all') {
                            fetchDivisions(officeTerm, divisionTerm);
                        }
                        if (divisionTerm !== 'all') {
                            fetchUnits(divisionTerm, unitTerm);
                        }
                        if (unitTerm !== 'all') {
                            fetchSubunits(unitTerm, subunitTerm);
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-red-500">
                                    Error loading search results. Please try again.
                                </td>
                            </tr>
                        `;
                    });
                }, 300); // Debounce for 300ms
            }
            
            // Function to attach event listeners
            function attachEventListeners() {
                // Delete functionality removed - no event listeners needed
                
                // Reattach pagination event listeners
                if (paginationSection) {
                    paginationSection.querySelectorAll('a').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            
                            // Show loading state
                            tableBody.innerHTML = `
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center">
                                        <div class="flex justify-center">
                                            <svg class="animate-spin h-5 w-5 text-[#1e6031]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            
                            // Fetch the page
                            fetch(this.href, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.table_body) {
                                    tableBody.innerHTML = data.table_body;
                                }
                                
                                if (data.pagination && paginationSection) {
                                    paginationSection.innerHTML = data.pagination;
                                }
                                
                                // Reattach event listeners
                                attachEventListeners();
                                
                                // Load dependent dropdowns
                                const urlParams = new URLSearchParams(window.location.search);
                                const officeTerm = urlParams.get('office') || 'all';
                                const divisionTerm = urlParams.get('division') || 'all';
                                const unitTerm = urlParams.get('unit') || 'all';
                                
                                if (officeTerm !== 'all') {
                                    fetchDivisions(officeTerm, divisionTerm);
                                }
                                if (divisionTerm !== 'all') {
                                    fetchUnits(divisionTerm, unitTerm);
                                }
                                if (unitTerm !== 'all') {
                                    fetchSubunits(unitTerm, urlParams.get('subunit') || 'all');
                                }
                            })
                            .catch(error => {
                                console.error('Pagination error:', error);
                                tableBody.innerHTML = `
                                    <tr>
                                        <td colspan="10" class="px-6 py-4 text-center text-red-500">
                                            Error loading page. Please try again.
                                        </td>
                                    </tr>
                                `;
                            });
                        });
                    });
                }
            }
            
            // Add event listeners
            searchInput.addEventListener('input', performSearch);
            officeFilter.addEventListener('change', function() {
                const officeId = this.value;
                // Reset dependent filters
                divisionFilter.innerHTML = '<option value="all">All Divisions</option>';
                unitFilter.innerHTML = '<option value="all">All Units</option>';
                subunitFilter.innerHTML = '<option value="all">All Subunits</option>';
                performSearch();
                
                // Load divisions if office is selected
                if (officeId !== 'all') {
                    fetchDivisions(officeId);
                }
            });
            
            divisionFilter.addEventListener('change', function() {
                const divisionId = this.value;
                // Reset dependent filters
                unitFilter.innerHTML = '<option value="all">All Units</option>';
                subunitFilter.innerHTML = '<option value="all">All Subunits</option>';
                performSearch();
                
                // Load units if division is selected
                if (divisionId !== 'all') {
                    fetchUnits(divisionId);
                }
            });
            
            unitFilter.addEventListener('change', function() {
                const unitId = this.value;
                // Reset dependent filter
                subunitFilter.innerHTML = '<option value="all">All Subunits</option>';
                performSearch();
                
                // Load subunits if unit is selected
                if (unitId !== 'all') {
                    fetchSubunits(unitId);
                }
            });
            
            subunitFilter.addEventListener('change', performSearch);
            classFilter.addEventListener('change', performSearch);
            statusFilter.addEventListener('change', performSearch);
            
            // Functions to fetch dependent dropdown options
            function fetchDivisions(officeId, selectedDivision = 'all') {
                fetch(`{{ route('admin.employees.get-divisions-by-office') }}?office_id=${officeId}`)
                    .then(response => response.json())
                    .then(divisions => {
                        divisionFilter.innerHTML = '<option value="all">All Divisions</option>';
                        divisions.forEach(division => {
                            const selected = division.id == selectedDivision ? 'selected' : '';
                            divisionFilter.innerHTML += `<option value="${division.id}" ${selected}>${division.division_name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
            
            function fetchUnits(divisionId, selectedUnit = 'all') {
                fetch(`{{ route('admin.employees.get-units-by-division') }}?division_id=${divisionId}`)
                    .then(response => response.json())
                    .then(units => {
                        unitFilter.innerHTML = '<option value="all">All Units</option>';
                        units.forEach(unit => {
                            const selected = unit.id == selectedUnit ? 'selected' : '';
                            unitFilter.innerHTML += `<option value="${unit.id}" ${selected}>${unit.unit_name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
            
            function fetchSubunits(unitId, selectedSubunit = 'all') {
                fetch(`{{ route('admin.employees.get-subunits-by-unit') }}?unit_id=${unitId}`)
                    .then(response => response.json())
                    .then(subunits => {
                        subunitFilter.innerHTML = '<option value="all">All Subunits</option>';
                        subunits.forEach(subunit => {
                            const selected = subunit.id == selectedSubunit ? 'selected' : '';
                            subunitFilter.innerHTML += `<option value="${subunit.id}" ${selected}>${subunit.subunit_name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
            
            // Load dependent dropdowns on page load if filters are set
            const urlParams = new URLSearchParams(window.location.search);
            const officeTerm = urlParams.get('office') || 'all';
            const divisionTerm = urlParams.get('division') || 'all';
            const unitTerm = urlParams.get('unit') || 'all';
            const subunitTerm = urlParams.get('subunit') || 'all';
            
            if (officeTerm !== 'all') {
                fetchDivisions(officeTerm, divisionTerm);
            }
            if (divisionTerm !== 'all') {
                fetchUnits(divisionTerm, unitTerm);
            }
            if (unitTerm !== 'all') {
                fetchSubunits(unitTerm, subunitTerm);
            }
            
            // Initial attachment of event listeners
            attachEventListeners();
        });
        
        // Display success messages from session
        @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#1e6031'
        });
        @endif
    </script>
</x-app-layout>