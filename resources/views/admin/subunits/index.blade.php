<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-center items-center">
            <!-- Centered Content -->
            <div class="flex justify-between items-center" style="width: 100%; max-width: 1200px;">
                <!-- Left: Heading -->
                <h2 class="font-bold flex items-center" style="color: #1e6031; font-size: 1.5rem; height: 32px; margin-top: 10px;">
                    <svg class="mr-2" style="width: 24px; height: 24px; color: #1e6031;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Subunit Management
                </h2>

                <!-- Right: Search Bar + Add Subunit Button -->
                <div class="flex items-center gap-2">
                    <!-- Search Bar -->
                    <form id="searchForm" method="GET" action="{{ route('admin.subunits.index') }}">
                        <div class="flex">
                            <input type="text" name="search" class="form-input" placeholder="Search subunits..." 
                                   value="{{ request('search') }}"
                                   style="height: 32px; width: 250px; font-size: 0.85rem; border: 1px solid #1e6031; border-radius: 0.375rem 0 0 0.375rem; padding: 0 12px;">
                            <button type="submit" 
                                    style="background-color: #1e6031; color: #ffffff; border: 1px solid #1e6031; height: 32px; width: 40px; border-radius: 0 0.375rem 0.375rem 0; cursor:pointer; display: flex; align-items: center; justify-content: center;">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div> 
                    </form>
                    
                    <!-- Add New Subunit Button -->
                    <a href="{{ route('admin.subunits.create') }}" class="inline-flex items-center px-3 py-1 bg-[#1e6031] border border-[#1e6031] rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150" style="height: 32px;">
                        <svg class="mr-1" style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Subunit
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

<div class="-mt-5">
    <div class="flex justify-center">
        <div style="width: 100%; max-width: 1200px;">
                <!-- Filter -->
               <div class="flex justify-between items-center mb-0" style="display:flex; align-items:center; justify-content:space-between; padding:4px 0; border-bottom:1px solid #1e6031; margin-bottom:8px;">
                    <div style="display:flex; align-items:center;">
                        <label for="statusFilter" style="margin-right: 10px; font-weight: normal; font-size: 0.875rem;">Filter by Status: </label>
                        <select id="statusFilter" name="status" class="border border-gray-300 rounded-md px-2 py-1 text-sm"
                            style="width:150px; padding:6px 8px; font-size:13px; border:1px solid #ccc; border-radius:5px;">
                            <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="active" {{ request('status', 'all') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status', 'all') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

<!-- Subunits Table -->
<div class="w-full">
    <table class="w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead style="background-color: #1e6031; color: white;">
            <tr>
                <th style="padding: 8px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; width: 5%;">No.</th>
                <th style="padding: 8px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; width: 35%;">Subunit Name</th>
                <th style="padding: 8px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; width: 20%;">Abbreviation</th>
                <th style="padding: 8px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; width: 20%;">Status</th>
                <th style="padding: 8px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; width: 20%;">Actions</th>
            </tr>
        </thead>
        <tbody id="subunits-table-body">
            @include('admin.subunits.partials.table-body')
        </tbody>
    </table>
</div>

                <!-- Pagination -->
                <div class="flex justify-between items-center mt-4" id="pagination-container">
                    <div class="text-sm text-gray-600" id="pagination-info">
                        Showing {{ $subunits->firstItem() ?? 0 }} to {{ $subunits->lastItem() ?? 0 }} of {{ $subunits->total() }} results
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Custom Pagination - Copied from vehicles page -->
                        <nav>
                            <ul class="pagination">
                                <li class="page-item {{ $subunits->currentPage() <= 1 ? 'disabled' : '' }}">
                                    <a class="page-link {{ $subunits->currentPage() <= 1 ? 'disabled-link' : '' }}" 
                                       href="{{ $subunits->currentPage() <= 1 ? 'javascript:void(0)' : $subunits->previousPageUrl() }}"
                                       {{ $subunits->currentPage() <= 1 ? 'aria-disabled="true" tabindex="-1"' : '' }}>Prev</a>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">{{ $subunits->currentPage() }}</span>
                                </li>
                                <li class="page-item {{ $subunits->currentPage() >= $subunits->lastPage() ? 'disabled' : '' }}">
                                    <a class="page-link {{ $subunits->currentPage() >= $subunits->lastPage() ? 'disabled-link' : '' }}" 
                                       href="{{ $subunits->currentPage() >= $subunits->lastPage() ? 'javascript:void(0)' : $subunits->nextPageUrl() }}"
                                       {{ $subunits->currentPage() >= $subunits->lastPage() ? 'aria-disabled="true" tabindex="-1"' : '' }}>Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
/* Action buttons styling to match vehicles page */
.action-buttons .btn {
    font-size: 9px;
    padding: 2px 4px;
    line-height: 1;
    height: 24px;
    min-width: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 2px;
    border-radius: 3px;
    box-sizing: border-box;
}

.action-buttons .btn i,
.action-buttons .btn svg {
    font-size: 12px;
    margin-right: 2px;
    width: 12px;
    height: 12px;
}

/* Colors for action buttons */
.action-buttons .edit-btn {
    color: #ffc107 !important;
    border: 1px solid #ffc107 !important;
    background-color: transparent !important;
}

.action-buttons .edit-btn:hover {
    background-color: #ffc107 !important;
    color: #000 !important;
    border-color: #ffc107 !important;
}

.action-buttons .delete-btn {
    color: #dc3545 !important;
    border: 1px solid #dc3545 !important;
    background-color: transparent !important;
}

.action-buttons .delete-btn:hover {
    background-color: #dc3545 !important;
    color: #fff !important;
    border-color: #dc3545 !important;
}

/* Pagination styling - Simplified version */
.pagination {
    display: flex;
    justify-content: flex-end;
    list-style: none;
}

.pagination .page-link {
    color: #1e6031 !important;
    padding: 0.15rem 0.4rem;
    font-size: 0.8125rem;
    display: block;
    text-decoration: none;
    background-color: #fff !important;
    border: 1px solid #1e6031;
    border-radius: 0.25rem;
}

.page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
    cursor: not-allowed;
}

.page-item:not(.disabled) .page-link:hover {
    background-color: #1e6031 !important;
    color: white !important;
}

.page-link.disabled-link {
    background-color: #e9ecef !important;
    border-color: #dee2e6 !important;
    color: #6c757d !important;
    cursor: not-allowed !important;
    pointer-events: none;
}

.pagination .active .page-link {
    background-color: #1e6031 !important;
    color: white !important;
    font-weight: bold;
}

.page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
}

.page-item {
    margin: 0 2px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle status filter change
    const statusFilter = document.getElementById('statusFilter');
    if(statusFilter) {
        statusFilter.addEventListener('change', function() {
            const searchValue = document.querySelector('[name="search"]').value;
            const statusValue = this.value;
            
            // Build new URL with filters
            let url = new URL(window.location.href);
            url.searchParams.set('search', searchValue);
            if(statusValue && statusValue !== 'all') {
                url.searchParams.set('status', statusValue);
            } else {
                url.searchParams.delete('status');
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
                window.location.href = '{{ route("admin.subunits.index") }}';
                e.preventDefault();
            }
        });
    }
});
</script>
</x-admin-layout>