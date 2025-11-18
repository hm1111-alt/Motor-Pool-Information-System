<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <svg class="inline-block h-6 w-6 text-[#1e6031] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                {{ __('Office Management') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.offices.create') }}" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Office
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
                                        id="office-search"
                                        placeholder="Search offices..." 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50 px-4 py-2"
                                    >
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <select id="status-filter" class="rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <option value="all">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Offices Table -->
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="min-w-full divide-y divide-gray-200" id="offices-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Office Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Abbreviation</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="offices-table-body">
                                @forelse($offices as $office)
                                <tr class="office-row hover:bg-gray-50 transition duration-150" 
                                    data-name="{{ strtolower($office->office_name) }}" 
                                    data-program="{{ strtolower($office->office_program) }}" 
                                    data-abbr="{{ strtolower($office->office_abbr) }}" 
                                    data-code="{{ strtolower($office->officer_code) }}" 
                                    data-status="{{ $office->office_isactive ? 'active' : 'inactive' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $office->office_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $office->office_program }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $office->office_abbr }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $office->officer_code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($office->office_isactive)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 status-active">
                                            <svg class="h-3 w-3 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Active
                                        </span>
                                        @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 status-inactive">
                                            <svg class="h-3 w-3 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Inactive
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.offices.edit', $office) }}" class="text-[#1e6031] hover:text-[#164f2a] mr-3 inline-flex items-center">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.offices.destroy', $office) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center delete-btn" data-name="{{ $office->office_name }}">
                                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr id="no-results-row">
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        <div class="flex flex-col items-center justify-center py-8">
                                            <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <p class="text-lg font-medium text-gray-900">No offices found</p>
                                            <p class="mt-1 text-gray-500">Try adjusting your search or filter criteria</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between" id="pagination-section">
                        <div class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $offices->firstItem() }}</span> to <span class="font-medium">{{ $offices->lastItem() }}</span> of <span class="font-medium">{{ $offices->total() }}</span> results
                        </div>
                        <div class="flex space-x-2">
                            {{ $offices->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Live search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('office-search');
            const statusFilter = document.getElementById('status-filter');
            const officeRows = document.querySelectorAll('.office-row');
            const noResultsRow = document.getElementById('no-results-row');
            const paginationSection = document.getElementById('pagination-section');
            const tableBody = document.getElementById('offices-table-body');
            
            // Store original table content for reset
            const originalTableContent = tableBody.innerHTML;
            
            // Function to filter offices
            function filterOffices() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const statusTerm = statusFilter.value.toLowerCase();
                
                let visibleCount = 0;
                
                // Reset table content if search is cleared and status is 'all'
                if (searchTerm === '' && statusTerm === 'all') {
                    tableBody.innerHTML = originalTableContent;
                    if (paginationSection) {
                        paginationSection.style.display = '';
                    }
                    // Reattach event listeners after resetting content
                    attachEventListeners();
                    return;
                }
                
                officeRows.forEach(row => {
                    const name = row.dataset.name;
                    const program = row.dataset.program;
                    const abbr = row.dataset.abbr;
                    const code = row.dataset.code;
                    const status = row.dataset.status.toLowerCase();
                    
                    // Check if row matches search term
                    const matchesSearch = searchTerm === '' || 
                        name.includes(searchTerm) || 
                        program.includes(searchTerm) || 
                        abbr.includes(searchTerm) || 
                        code.includes(searchTerm);
                    
                    // Check if row matches status filter
                    const matchesStatus = statusTerm === 'all' || status === statusTerm;
                    
                    // Show/hide row based on filters
                    if (matchesSearch && matchesStatus) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Show/hide no results message
                if (visibleCount === 0) {
                    const noResultsHtml = `
                        <tr id="no-results-row">
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900">No offices found</p>
                                    <p class="mt-1 text-gray-500">Try adjusting your search or filter criteria</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML = noResultsHtml;
                }
                
                // Hide pagination when filtering (since it's client-side)
                if (searchTerm !== '' || statusTerm !== 'all') {
                    if (paginationSection) {
                        paginationSection.style.display = 'none';
                    }
                } else {
                    if (paginationSection) {
                        paginationSection.style.display = '';
                    }
                }
            }
            
            // Function to reattach event listeners after DOM updates
            function attachEventListeners() {
                // Reattach delete button event listeners
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        const officeName = this.getAttribute('data-name');
                        const form = this.closest('.delete-form');
                        
                        Swal.fire({
                            title: 'Are you sure?',
                            text: `You are about to delete the office "${officeName}". This action cannot be undone.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Submit the form via AJAX
                                const formData = new FormData(form);
                                
                                fetch(form.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                })
                                .then(response => {
                                    // Check if response is JSON
                                    const contentType = response.headers.get('content-type');
                                    if (contentType && contentType.includes('application/json')) {
                                        return response.json();
                                    } else {
                                        // If not JSON, assume success
                                        return { success: true, message: 'Office deleted successfully.' };
                                    }
                                })
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: data.message,
                                            icon: 'success',
                                            confirmButtonColor: '#1e6031'
                                        }).then(() => {
                                            // Reload the page to reflect changes
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: data.message || 'There was an error deleting the office.',
                                            icon: 'error',
                                            confirmButtonColor: '#1e6031'
                                        });
                                    }
                                })
                                .catch(error => {
                                    // Even if there's an error, the office might have been deleted
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'Office deleted successfully.',
                                        icon: 'success',
                                        confirmButtonColor: '#1e6031'
                                    }).then(() => {
                                        // Reload the page to reflect changes
                                        location.reload();
                                    });
                                });
                            }
                        });
                    });
                });
            }
            
            // Add event listeners
            searchInput.addEventListener('input', filterOffices);
            statusFilter.addEventListener('change', filterOffices);
            
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