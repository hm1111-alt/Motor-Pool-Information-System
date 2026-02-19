@extends('layouts.motorpool-admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-3">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-4 w-4 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="main">
            <div class="flex justify-between items-center mb-3">
                <!-- Left: Heading -->
                <h2 class="font-semibold text-xl text-gray-800">Vehicles</h2>

                <!-- Right: Search Bar + Add Vehicle Button -->
                <div class="flex items-center gap-2">
                    <!-- Search Bar -->
                    <form id="searchForm" method="GET" action="{{ route('vehicles.index') }}">
                        <div class="flex">
                            <input type="text" name="search" class="form-input" placeholder="Search vehicles..." 
                                   value="{{ request('search') }}"
                                   style="height: 32px; width: 250px; font-size: 0.85rem; border: 1px solid #1e6031; border-radius: 0.375rem 0 0 0.375rem; padding: 0 12px;">
                            <button type="submit" 
                                    style="background-color: #1e6031; color: #ffffff; border: 1px solid #1e6031; height: 32px; width: 40px; border-radius: 0 0.375rem 0.375rem 0; cursor:pointer;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div> 
                    </form>
                    
                    <a href="{{ route('vehicles.create') }}" class="inline-flex items-center px-3 py-1.5 bg-[#1e6031] border border-[#1e6031] rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-1 text-xs"></i> Add New Vehicle
                    </a>
                </div>
            </div>

            <!-- Filter + PDF Button -->
            <div class="flex justify-between items-center mb-2" style="display:flex; align-items:center; justify-content:space-between; padding:5px; border-bottom:1px solid #1e6031; margin-bottom:5px;">
                
                <!-- Type Filter -->
                <div style="display:flex; align-items:center;">
                    <label for="typeFilter" style="margin-right: 10px; font-weight: bold;">Filter by Type:</label>
                    <select id="typeFilter" name="type" class="border border-gray-300 rounded-md px-2 py-1 text-sm"
                        style="width:150px; padding:6px 8px; font-size:13px; border:1px solid #ccc; border-radius:5px;">
                        <option value="">All Types</option>
                        @foreach($types as $typeOption)
                        <option value="{{ $typeOption }}" {{ request('type') == $typeOption ? 'selected' : '' }}>{{ $typeOption }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Generate PDF Button -->
               <div class="flex justify-end mb-2">
               <button id="generatePDFBtn" class="inline-flex items-center px-2 py-1 bg-red-600 border border-red-600 rounded text-xs text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" style="padding: 4px 8px; font-size: 0.80rem;" disabled>
                <i class="fas fa-file-pdf mr-1 text-xs"></i> Generate PDF
            </button>
               </div>

            </div>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

            <!-- Vehicles Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead style="background-color: #1e6031; color: white;">
                        <tr>
                            <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">No.</th>
                            <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Plate Number</th>
                            <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Model</th>
                            <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Type</th>
                            <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Action</th>
                        </tr>
                    </thead>
                  <tbody id="vehicle-table-body">
                    @forelse($vehicles as $index => $vehicle)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-[#f0f8f0]">
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">{{ $index + 1 }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">{{ $vehicle->plate_number }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">{{ $vehicle->model }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-600 max-w-[200px]">
                                <div class="text-[#004d00] font-medium">{{ $vehicle->type }}</div>
                                <div class="text-[#006400] text-xs">{{ $vehicle->fuel_type ?? 'N/A' }}</div>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-center text-xs">
                                <div class="action-buttons flex justify-center space-x-1">
                                    <a href="{{ route('vehicles.show', $vehicle) }}" 
                                       class="btn view-btn border inline-flex items-center justify-center"
                                       title="View Vehicle">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('vehicles.edit', $vehicle) }}" 
                                       class="btn edit-btn border inline-flex items-center justify-center"
                                       title="Edit Vehicle">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn archive-btn border delete-btn inline-flex items-center justify-center"
                                                title="Delete Vehicle">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-6 text-center text-xs text-gray-500">
                                No vehicles found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4">
                <div class="text-sm text-gray-600">
                    Showing {{ $vehicles->firstItem() ?? 0 }} to {{ $vehicles->lastItem() ?? 0 }} of {{ $vehicles->total() }} applications
                </div>
                <div class="flex items-center space-x-2">
                    @if($vehicles->lastPage() > 1)
                        {{ $vehicles->appends(['search' => request('search'), 'status' => request('status')])->links() }}
                    @else
                        <nav>
                            <ul class="pagination">
                                <li class="page-item disabled">
                                    <span class="page-link">Prev</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>

<style>
.action-buttons .btn {
    font-size: 10px;          /* smaller text */
    padding: 2px 6px;         /* slightly wider padding */
    line-height: 1;           
    height: 25px;             /* small consistent height */
    min-width: 50px;          /* ensures buttons aren't too narrow */
    display: inline-flex;     
    align-items: center;      
    justify-content: center;
    gap: 3px;                 /* small gap between icon and text */
    border-radius: 4px;       /* tight corners */
}

/* Icons inside buttons */
.action-buttons .btn i {
    font-size: 10px;
    margin-right: 2px;
}

/* Colors (same as before) - More specific to override Bootstrap */
.action-buttons .view-btn {
    color: #0d6efd !important;
    border: 1px solid #0d6efd !important;
    background-color: transparent !important;
}
.action-buttons .view-btn:hover {
    background-color: #0d6efd !important;
    color: #fff !important;
    border-color: #0d6efd !important;
}

.action-buttons .edit-btn {
    color: #ffc107 !important;
    border: 1px solid #ffc107 !important;
    border: 1px solid #ffc107 !important;
    background-color: transparent !important;
}
.action-buttons .edit-btn:hover {
    background-color: #ffc107 !important;
    color: #000 !important;
    border-color: #ffc107 !important;
}

.action-buttons .archive-btn {
    color: #dc3545 !important;
    border: 1px solid #dc3545 !important;
    background-color: transparent !important;
}
.action-buttons .archive-btn:hover {
    background-color: #dc3545 !important;
    color: #fff !important;
    border-color: #dc3545 !important;
}

.btn-outline-danger {
    font-size: 12px;
    padding: 5px 10px;
}

.highlight-updated {
    background-color: #d4edda !important; /* light green */
    transition: background-color 0.5s ease;
}

/* Pagination styling */
.pagination {
    display: flex;
    justify-content: flex-end;
}

.pagination .page-link {
    color: #1e6031;
    border-color: #1e6031;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.pagination .page-link:hover {
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
}

.pagination .active .page-link {
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
}

/* Hide default Previous/Next links and style custom ones */
.page-item:first-child .page-link,
.page-item:last-child .page-link {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
}

.page-item:first-child .page-link:hover,
.page-item:last-child .page-link:hover {
    background-color: #164f2a;
    border-color: #164f2a;
}

.page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
}

.items-per-page {
    display: flex;
    align-items: center;
    min-width: 100px;
}

/* Manual pagination for single page */
.pagination {
    display: flex;
    list-style: none;
}

.page-item {
    margin: 0 2px;
}

.page-link {
    display: block;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    color: #1e6031;
    background-color: #fff;
    border: 1px solid #1e6031;
    text-decoration: none;
    border-radius: 0.25rem;
}

.page-item.active .page-link {
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
}

.page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
    cursor: not-allowed;
}
</style>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if current URL has search or filter parameters
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters = urlParams.has('search') || urlParams.has('type');
        
        // If there are no filters, ensure the search input is clear
        if(!hasFilters) {
            const searchInput = document.querySelector('[name="search"]');
            if(searchInput) {
                searchInput.value = '';
            }
        }
        
        // Handle type filter change
        const typeFilter = document.getElementById('typeFilter');
        if(typeFilter) {
            typeFilter.addEventListener('change', function() {
                const searchValue = document.querySelector('[name="search"]').value;
                const typeValue = this.value;
                
                // Build new URL with filters
                let url = new URL(window.location.href);
                url.searchParams.set('search', searchValue);
                if(typeValue) {
                    url.searchParams.set('type', typeValue);
                } else {
                    url.searchParams.delete('type');
                }
                
                window.location.href = url.toString();
            });
        }
        
        // Handle search form submission
        const searchForm = document.getElementById('searchForm');
        if(searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const searchInput = document.querySelector('[name="search"]');
                if(searchInput && searchInput.value.trim() === '') {
                    // If search is empty, redirect to base URL to remove all parameters
                    window.location.href = '{{ route("vehicles.index") }}';
                    e.preventDefault();
                }
            });
        }
        
        // Handle delete button clicks
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                if (confirm('Are you sure you want to delete this vehicle?')) {
                    form.submit();
                }
            });
        });
        
        // Disable PDF generation since it's not implemented yet
        const generatePDFBtn = document.getElementById('generatePDFBtn');
        if(generatePDFBtn) {
            generatePDFBtn.addEventListener('click', function(e) {
                e.preventDefault();
                alert('PDF generation functionality will be implemented soon.');
            });
        }
    });
</script>
@endsection