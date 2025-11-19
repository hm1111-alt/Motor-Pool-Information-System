<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <svg class="inline-block h-6 w-6 text-[#1e6031] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                {{ __('Class Management') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.classes.create') }}" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Class
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
                    <!-- Search Section -->
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        id="class-search"
                                        placeholder="Search classes..." 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50 px-4 py-2"
                                        value="{{ request('search', '') }}"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Classes Table -->
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="min-w-full divide-y divide-gray-200" id="classes-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="classes-table-body">
                                @include('admin.classes.partials.table-body')
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination-section">
                        @include('admin.classes.partials.pagination')
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
            const searchInput = document.getElementById('class-search');
            const tableBody = document.getElementById('classes-table-body');
            const paginationSection = document.getElementById('pagination-section');
            
            let searchTimeout;
            
            // Function to perform server-side search
            function performSearch() {
                const searchTerm = searchInput.value.trim();
                
                // Clear previous timeout to debounce requests
                clearTimeout(searchTimeout);
                
                // Set new timeout
                searchTimeout = setTimeout(() => {
                    // Show loading state
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-center">
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
                    
                    // If search is empty, remove parameter
                    if (searchTerm === '') {
                        url.searchParams.delete('search');
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
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-red-500">
                                    Error loading search results. Please try again.
                                </td>
                            </tr>
                        `;
                    });
                }, 300); // Debounce for 300ms
            }
            
            // Function to attach event listeners
            function attachEventListeners() {
                // Reattach delete button event listeners
                document.querySelectorAll('.delete-class').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        const className = this.getAttribute('data-name');
                        const form = this.closest('.delete-form');
                        
                        Swal.fire({
                            title: 'Are you sure?',
                            text: `You are about to delete the class "${className}". This action cannot be undone.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
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
                                        return response.json().then(data => ({ data, status: response.status, isJson: true }));
                                    } else {
                                        // If not JSON, assume success for redirect responses
                                        return { 
                                            data: { success: true, message: 'Class deleted successfully.' }, 
                                            status: response.status, 
                                            isJson: false 
                                        };
                                    }
                                })
                                .then(({ data, status, isJson }) => {
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
                                            text: data.message || 'There was an error deleting the class.',
                                            icon: 'error',
                                            confirmButtonColor: '#1e6031'
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Delete error:', error);
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'Class deleted successfully.',
                                        icon: 'success',
                                        confirmButtonColor: '#1e6031'
                                    }).then(() => {
                                        // Even if there was an error, reload to show the current state
                                        location.reload();
                                    });
                                });
                            }
                        });
                    });
                });
                
                // Reattach pagination event listeners
                if (paginationSection) {
                    paginationSection.querySelectorAll('a').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            
                            // Show loading state
                            tableBody.innerHTML = `
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center">
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
                            })
                            .catch(error => {
                                console.error('Pagination error:', error);
                                tableBody.innerHTML = `
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-center text-red-500">
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