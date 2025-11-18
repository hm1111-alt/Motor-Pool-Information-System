<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <svg class="inline-block h-6 w-6 text-[#1e6031] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                {{ __('Subunit Management') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.subunits.create') }}" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Subunit
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
                                        id="searchInput"
                                        placeholder="Search subunits..." 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50 pl-10 pr-4 py-2"
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <select id="unitFilter" class="rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <option value="all">All Units</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                    @endforeach
                                </select>
                                <select id="statusFilter" class="rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <option value="all">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <button id="resetFilters" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition duration-300 flex items-center">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Subunits Table -->
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subunit Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Abbreviation</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="subunitsTableBody">
                                @include('admin.subunits.partials.table-body')
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between" id="paginationContainer">
                        @include('admin.subunits.partials.pagination')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Confirm Delete</h3>
                <p class="text-gray-600 mb-4">Are you sure you want to delete this subunit? This action cannot be undone.</p>
                <div class="flex justify-end space-x-3">
                    <button id="cancelDelete" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                        Cancel
                    </button>
                    <button id="confirmDelete" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const unitFilter = document.getElementById('unitFilter');
            const statusFilter = document.getElementById('statusFilter');
            const resetFilters = document.getElementById('resetFilters');
            const subunitsTableBody = document.getElementById('subunitsTableBody');
            const paginationContainer = document.getElementById('paginationContainer');
            const deleteModal = document.getElementById('deleteModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            
            let currentDeleteUrl = null;
            let currentSearch = '';
            let currentUnit = 'all';
            let currentStatus = 'all';
            let currentPage = 1;

            // Function to fetch and update table data
            function fetchSubunits() {
                const url = new URL('{{ route("admin.subunits.index") }}', window.location.origin);
                url.searchParams.append('search', currentSearch);
                url.searchParams.append('unit', currentUnit);
                url.searchParams.append('status', currentStatus);
                url.searchParams.append('page', currentPage);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    subunitsTableBody.innerHTML = data.table_body;
                    paginationContainer.innerHTML = data.pagination;
                    attachEventListeners();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to load subunits data', 'error');
                });
            }

            // Debounce function for search input
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Search with debounce
            const debouncedSearch = debounce(function() {
                currentSearch = searchInput.value;
                currentPage = 1;
                fetchSubunits();
            }, 300);

            // Event listeners for filters
            searchInput.addEventListener('input', debouncedSearch);
            
            unitFilter.addEventListener('change', function() {
                currentUnit = this.value;
                currentPage = 1;
                fetchSubunits();
            });
            
            statusFilter.addEventListener('change', function() {
                currentStatus = this.value;
                currentPage = 1;
                fetchSubunits();
            });
            
            resetFilters.addEventListener('click', function() {
                searchInput.value = '';
                unitFilter.value = 'all';
                statusFilter.value = 'all';
                currentSearch = '';
                currentUnit = 'all';
                currentStatus = 'all';
                currentPage = 1;
                fetchSubunits();
            });

            // Pagination event delegation
            paginationContainer.addEventListener('click', function(e) {
                if (e.target.closest('a')) {
                    e.preventDefault();
                    const url = new URL(e.target.closest('a').href);
                    currentPage = new URLSearchParams(url.search).get('page') || 1;
                    fetchSubunits();
                }
            });

            // Function to attach event listeners to dynamically created elements
            function attachEventListeners() {
                // Edit buttons
                document.querySelectorAll('.edit-subunit').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        window.location.href = this.href;
                    });
                });

                // Delete buttons
                document.querySelectorAll('.delete-subunit').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        currentDeleteUrl = this.href;
                        deleteModal.classList.remove('hidden');
                        deleteModal.classList.add('flex');
                    });
                });
            }

            // Delete confirmation
            cancelDelete.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
                deleteModal.classList.remove('flex');
                currentDeleteUrl = null;
            });

            confirmDelete.addEventListener('click', function() {
                if (currentDeleteUrl) {
                    fetch(currentDeleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        deleteModal.classList.add('hidden');
                        deleteModal.classList.remove('flex');
                        currentDeleteUrl = null;
                        
                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success');
                            fetchSubunits(); // Refresh the table
                        } else {
                            Swal.fire('Error!', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        deleteModal.classList.add('hidden');
                        deleteModal.classList.remove('flex');
                        currentDeleteUrl = null;
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Failed to delete subunit', 'error');
                    });
                }
            });

            // Initial load
            attachEventListeners();
        });
    </script>
</x-app-layout>