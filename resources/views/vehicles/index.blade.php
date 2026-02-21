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
                    
                    <button type="button" id="addVehicleBtn" class="inline-flex items-center px-3 py-1.5 bg-[#1e6031] border border-[#1e6031] rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-1 text-xs"></i> Add New Vehicle
                    </button>
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
               <button id="generatePDFBtn" class="inline-flex items-center px-2 py-1 bg-red-600 border border-red-600 rounded text-xs text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" style="padding: 4px 8px; font-size: 0.80rem;">
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

            <!-- Hidden data container for PDF generation -->
            <div id="js-vehicle-data" data-vehicles='@json($activeVehicles->toArray())'></div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4" id="pagination-container" data-current-page="{{ $vehicles->currentPage() }}" data-last-page="{{ $vehicles->lastPage() }}">
                <div class="text-sm text-gray-600" id="pagination-info">
                    Showing {{ $vehicles->firstItem() ?? 0 }} to {{ $vehicles->lastItem() ?? 0 }} of {{ $vehicles->total() }} vehicles
                </div>
                <div class="flex items-center space-x-2">
                    <nav>
                        <ul class="pagination">
                            <li class="page-item {{ $vehicles->currentPage() <= 1 ? 'disabled' : '' }}">
                                <a class="page-link {{ $vehicles->currentPage() <= 1 ? 'disabled-link' : '' }}" href="#" onclick="loadPage({{ max(1, $vehicles->currentPage() - 1) }}); return false;" {{ $vehicles->currentPage() <= 1 ? 'aria-disabled="true"' : '' }}>Prev</a>
                            </li>
                            <li class="page-item active">
                                <span class="page-link" id="current-page">{{ $vehicles->currentPage() }}</span>
                            </li>
                            <li class="page-item {{ $vehicles->currentPage() >= $vehicles->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link {{ $vehicles->currentPage() >= $vehicles->lastPage() ? 'disabled-link' : '' }}" href="#" onclick="loadPage({{ min($vehicles->lastPage(), $vehicles->currentPage() + 1) }}); return false;" {{ $vehicles->currentPage() >= $vehicles->lastPage() ? 'aria-disabled="true"' : '' }}>Next</a>
                            </li>
                        </ul>
                    </nav>
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
    color: #6c757d !important;
    border-color: #dee2e6 !important;
    background-color: #fff !important;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.page-item:not(.disabled) .page-link:hover {
    background-color: #1e6031 !important;
    border-color: #1e6031 !important;
    color: white !important;
}

.pagination .active .page-link {
    background-color: #1e6031 !important;
    border-color: #1e6031 !important;
    color: white !important;
    font-weight: bold;
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
    background-color: #e9ecef !important;
    border-color: #dee2e6 !important;
    color: #6c757d !important;
    cursor: not-allowed !important;
}

.page-item.disabled .page-link:hover {
    background-color: #e9ecef !important;
    border-color: #dee2e6 !important;
    color: #6c757d !important;
    cursor: not-allowed !important;
    pointer-events: none !important;
}

.page-link.disabled-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}

.page-link:not(.disabled-link) {
    color: #6c757d; /* Same color as disabled links */
    border-color: #dee2e6;
    background-color: #fff;
}

.page-link:not(.disabled-link):hover {
    background-color: #e9ecef; /* Light gray on hover */
    border-color: #adb5bd;
    color: #495057;
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
        

        
        // Modal functionality using Bootstrap
        const addVehicleBtn = document.getElementById('addVehicleBtn');
        const submitVehicleBtn = document.getElementById('submitVehicleBtn');
        const modal = new bootstrap.Modal(document.getElementById('addVehicleModal'));
        
        // Open modal - preserve data unless it's a fresh start
        if(addVehicleBtn) {
            addVehicleBtn.addEventListener('click', function() {
                // Check if form already has data
                const form = document.getElementById('addVehicleForm');
                const hasExistingData = Array.from(form.elements).some(element => 
                    element.type !== 'submit' && 
                    element.type !== 'button' && 
                    element.value && 
                    element.value.trim() !== ''
                );
                
                // Only reset if no existing data (fresh start)
                if (!hasExistingData) {
                    form.reset();
                }
                
                modal.show();
            });
        }
        
        // Handle cancel button - always reset form
        document.addEventListener('click', function(e) {
            if (e.target.hasAttribute('data-bs-dismiss') && e.target.getAttribute('data-bs-dismiss') === 'modal') {
                document.getElementById('addVehicleForm').reset();
            }
        });
        
        // Close modal function - don't reset form
        function closeModal() {
            modal.hide();
        }
        
        // Handle form submission with native HTML5 validation
        if(submitVehicleBtn) {
            submitVehicleBtn.addEventListener('click', function() {
                const form = document.getElementById('addVehicleForm');
                
                // Let HTML5 validation handle required fields
                if (!form.checkValidity()) {
                    // Trigger native browser validation
                    form.reportValidity();
                    return;
                }
                
                // If form is valid, submit via AJAX
                const formData = new FormData(form);
                
                // Show loading first
                Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait while the vehicle is being saved.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                
                fetch('{{ route("vehicles.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Keep loading for 2 seconds then show success
                        setTimeout(() => {
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Saved!',
                                text: 'Vehicle added successfully!',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                closeModal();
                                window.location.reload();
                            });
                        }, 2000);
                    } else {
                        // Close the loading alert immediately for errors
                        Swal.close();
                        
                        // Show validation errors using SweetAlert
                        if(data.errors) {
                            let errorMessages = [];
                            Object.values(data.errors).forEach(error => {
                                errorMessages.push(error[0]);
                            });
                            Swal.fire({
                                icon: 'warning',
                                title: 'Warning',
                                html: errorMessages.join('<br>'),
                                confirmButtonText: 'OK'
                            });
                        } else if(data.message) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                })
                .catch(error => {
                    // Close the loading alert
                    Swal.close();
                    console.error('Error:', error);
                    alert('An error occurred while adding the vehicle.');
                });
            });
        }
    });
    
    // AJAX pagination function to load data without changing URL
    async function loadPage(page) {
        const searchValue = document.querySelector('[name="search"]')?.value || '';
        const typeValue = document.getElementById('typeFilter')?.value || '';
        
        try {
            // Show smoother loading transition
            const tableBody = document.getElementById('vehicle-table-body');
            
            // Fade out current content
            tableBody.style.opacity = '0.5';
            tableBody.style.transition = 'opacity 0.2s ease-in-out';
            
            // Make AJAX request to get new page data
            const params = new URLSearchParams();
            params.append('page', page);
            if(searchValue) params.append('search', searchValue);
            if(typeValue) params.append('type', typeValue);
            params.append('ajax', 1);
            
            const response = await fetch('{{ route("vehicles.index") }}?' + params.toString());
            const data = await response.json();
            
            if (data.table_body && data.pagination_info) {
                // Update table content with smooth transition
                const tableBody = document.getElementById('vehicle-table-body');
                tableBody.innerHTML = data.table_body;
                
                // Fade in new content
                tableBody.style.opacity = '1';
                tableBody.style.transition = 'opacity 0.2s ease-in-out';
                
                // Update pagination info
                document.getElementById('pagination-info').textContent = 
                    `Showing ${data.pagination_info.first_item} to ${data.pagination_info.last_item} of ${data.pagination_info.total} vehicles`;
                
                // Update pagination controls
                const currentPage = data.pagination_info.current_page;
                const lastPage = data.pagination_info.last_page;
                const prevLink = document.querySelector('.pagination .page-item:nth-child(1) .page-link');
                const currentSpan = document.getElementById('current-page');
                const nextLink = document.querySelector('.pagination .page-item:nth-child(3) .page-link');
                
                // Update current page display
                currentSpan.textContent = currentPage;
                
                // Update previous button
                const prevItem = document.querySelector('.pagination .page-item:nth-child(1)');
                if (currentPage <= 1) {
                    prevItem.classList.add('disabled');
                    prevLink.removeAttribute('onclick');
                    prevLink.setAttribute('aria-disabled', 'true');
                } else {
                    prevItem.classList.remove('disabled');
                    prevLink.onclick = function() { loadPage(currentPage - 1); return false; };
                    prevLink.removeAttribute('aria-disabled');
                }
                
                // Update next button
                const nextItem = document.querySelector('.pagination .page-item:nth-child(3)');
                if (currentPage >= lastPage) {
                    nextItem.classList.add('disabled');
                    nextLink.removeAttribute('onclick');
                    nextLink.setAttribute('aria-disabled', 'true');
                } else {
                    nextItem.classList.remove('disabled');
                    nextLink.onclick = function() { loadPage(currentPage + 1); return false; };
                    nextLink.removeAttribute('aria-disabled');
                }
                
                // Update data attributes
                const container = document.getElementById('pagination-container');
                container.setAttribute('data-current-page', currentPage);
                container.setAttribute('data-last-page', lastPage);
            }
        } catch (error) {
            console.error('Error loading page:', error);
            const tableBody = document.getElementById('vehicle-table-body');
            tableBody.innerHTML = '<tr><td colspan="6" class="px-3 py-6 text-center text-xs text-red-500">Error loading data. Please try again.</td></tr>';
            
            // Ensure the table is visible
            tableBody.style.opacity = '1';
        }
    }
</script>

<!-- JS Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<!-- JS for Vehicle PDF generation -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('generatePDFBtn');
    if (!btn) return;

    btn.addEventListener('click', function () {
        // Show loading alert
        Swal.fire({
            title: 'Generating PDF...',
            text: 'Please wait while we prepare your vehicle list',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Wait 2 seconds then generate PDF and close loading
        setTimeout(function() {
            // Close the loading alert
            Swal.close();
            
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();

            const allVehicles = JSON.parse(document.getElementById('js-vehicle-data').dataset.vehicles);

        const logo = new Image();
        logo.src = "{{ asset('assets/images/clsu-logo.png') }}";

        logo.onload = function () {
            const logoSize = 30;  // Increased from 20 to 30 for better visibility
            const marginTop = 12;
            const logoX = pageWidth / 2 - 70;  // Adjusted position for larger logo

            // Header
            doc.addImage(logo, "PNG", logoX, marginTop, logoSize, logoSize);
            doc.setFont("helvetica", "normal").setFontSize(10);
            doc.text("Republic of the Philippines", pageWidth / 2, marginTop + 2, { align: "center" });
            doc.setFont("helvetica", "bold").setFontSize(14);
            doc.text("CENTRAL LUZON STATE UNIVERSITY", pageWidth / 2, marginTop + 8, { align: "center" });
            doc.setFont("helvetica", "normal").setFontSize(10);
            doc.text("Science City of Muñoz, Nueva Ecija", pageWidth / 2, marginTop + 14, { align: "center" });
            doc.setFont("helvetica", "bold").setFontSize(11);
            doc.text("TRANSPORTATION SERVICES", pageWidth / 2, marginTop + 20, { align: "center" });

            // Line under header
            doc.setDrawColor(0, 77, 0);
            doc.setLineWidth(0.5);
            doc.line(15, marginTop + 33, pageWidth - 15, marginTop + 33);

            // Title
            doc.setFontSize(12);
            doc.setFont("helvetica", "bold");
            doc.text("LIST OF VEHICLES", pageWidth / 2, marginTop + 42, { align: 'center' });

            // Prepare table data
            const tableData = allVehicles.length
                ? allVehicles.map((vehicle, index) => [
                    index + 1,
                    vehicle.plate_number || 'N/A',
                    vehicle.model || 'N/A',
                    vehicle.type || 'N/A',
                    vehicle.status || 'N/A'
                ])
                : null;

            if (tableData && tableData.length > 0) {
                doc.autoTable({
                    head: [["No.", "Plate Number", "Model", "Type", "Status"]],
                    body: tableData,
                    startY: marginTop + 50,
                    theme: 'grid',
                    headStyles: { fillColor: [0, 128, 0], textColor: 255, fontStyle: 'bold', halign: 'center' },
                    bodyStyles: { halign: 'center', valign: 'middle' },
                    styles: { fontSize: 9, cellPadding: 2 },  // Smaller font for more data
                    columnStyles: {  // Custom column widths
                        0: { cellWidth: 15 },   // No.
                        1: { cellWidth: 40 },   // Plate Number
                        2: { cellWidth: 50 },   // Model
                        3: { cellWidth: 40 },   // Type
                        4: { cellWidth: 40 }    // Status
                    },
                    didDrawPage: function (data) {
                        const pageCount = doc.internal.getNumberOfPages();
                        const pageNumber = doc.internal.getCurrentPageInfo().pageNumber;
                        doc.setFontSize(9);
                        doc.text(`Page ${pageNumber} of ${pageCount}`, pageWidth / 2, pageHeight - 8, { align: 'center' });
                    }
                });
            } else {
                doc.setFontSize(12);
                doc.setFont("helvetica", "bold");
                doc.text("No Vehicle Records Found", pageWidth / 2, marginTop + 60, { align: 'center' });
            }

            // Footer date
            const date = new Date();
            doc.setFontSize(9);
            doc.text(
                `Generated on: ${date.toLocaleDateString()} ${date.toLocaleTimeString()}`,
                pageWidth - 14,
                pageHeight - 10,
                { align: 'right' }
            );

            // Create a blob URL and open in modal
            const pdfBlob = doc.output('blob');
            const pdfUrl = URL.createObjectURL(pdfBlob);
            
            // Create modal HTML
            let modalHtml = `
                <div id="pdfPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
                        <div class="p-3 flex-grow overflow-auto">
                            <iframe src="${pdfUrl}" width="100%" height="650px" style="border: none;"></iframe>
                        </div>
                    </div>
                </div>
            `;
            
            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Close modal when clicking outside
            document.getElementById('pdfPreviewModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    const modal = document.getElementById('pdfPreviewModal');
                    if (modal) {
                        modal.remove();
                        // Revoke the object URL to free memory
                        URL.revokeObjectURL(pdfUrl);
                    }
                }
            });
        };
        
        logo.onerror = function () {
            // If logo fails to load, continue without it
            const marginTop = 12;

            // Header without logo
            doc.setFont("helvetica", "normal").setFontSize(10);
            doc.text("Republic of the Philippines", pageWidth / 2, marginTop + 2, { align: "center" });
            doc.setFont("helvetica", "bold").setFontSize(14);
            doc.text("CENTRAL LUZON STATE UNIVERSITY", pageWidth / 2, marginTop + 8, { align: "center" });
            doc.setFont("helvetica", "normal").setFontSize(10);
            doc.text("Science City of Muñoz, Nueva Ecija", pageWidth / 2, marginTop + 14, { align: "center" });
            doc.setFont("helvetica", "bold").setFontSize(11);
            doc.text("TRANSPORTATION SERVICES", pageWidth / 2, marginTop + 20, { align: "center" });

            // Line under header
            doc.setDrawColor(0, 77, 0);
            doc.setLineWidth(0.5);
            doc.line(15, marginTop + 33, pageWidth - 15, marginTop + 33);

            // Title
            doc.setFontSize(12);
            doc.setFont("helvetica", "bold");
            doc.text("LIST OF VEHICLES", pageWidth / 2, marginTop + 42, { align: 'center' });

            // Prepare table data
            const tableData = allVehicles.length
                ? allVehicles.map((vehicle, index) => [
                    index + 1,
                    vehicle.plate_number || 'N/A',
                    vehicle.model || 'N/A',
                    vehicle.type || 'N/A',
                    vehicle.status || 'N/A'
                ])
                : null;

            if (tableData && tableData.length > 0) {
                doc.autoTable({
                    head: [["No.", "Plate Number", "Model", "Type", "Status"]],
                    body: tableData,
                    startY: marginTop + 50,
                    theme: 'grid',
                    headStyles: { fillColor: [0, 128, 0], textColor: 255, fontStyle: 'bold', halign: 'center' },
                    bodyStyles: { halign: 'center', valign: 'middle' },
                    styles: { fontSize: 9, cellPadding: 2 },  // Smaller font for more data
                    columnStyles: {  // Custom column widths
                        0: { cellWidth: 15 },   // No.
                        1: { cellWidth: 40 },   // Plate Number
                        2: { cellWidth: 50 },   // Model
                        3: { cellWidth: 40 },   // Type
                        4: { cellWidth: 40 }    // Status
                    },
                    didDrawPage: function (data) {
                        const pageCount = doc.internal.getNumberOfPages();
                        const pageNumber = doc.internal.getCurrentPageInfo().pageNumber;
                        doc.setFontSize(9);
                        doc.text(`Page ${pageNumber} of ${pageCount}`, pageWidth / 2, pageHeight - 8, { align: 'center' });
                    }
                });
            } else {
                doc.setFontSize(12);
                doc.setFont("helvetica", "bold");
                doc.text("No Vehicle Records Found", pageWidth / 2, marginTop + 60, { align: 'center' });
            }

            // Footer date
            const date = new Date();
            doc.setFontSize(9);
            doc.text(
                `Generated on: ${date.toLocaleDateString()} ${date.toLocaleTimeString()}`,
                pageWidth - 14,
                pageHeight - 10,
                { align: 'right' }
            );

            // Create a blob URL and open in modal
            const pdfBlob = doc.output('blob');
            const pdfUrl = URL.createObjectURL(pdfBlob);
            
            // Create modal HTML
            let modalHtml = `
                <div id="pdfPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
                        <div class="p-3 flex-grow overflow-auto">
                            <iframe src="${pdfUrl}" width="100%" height="650px" style="border: none;"></iframe>
                        </div>
                    </div>
                </div>
            `;
            
            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Close modal when clicking outside
            document.getElementById('pdfPreviewModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    const modal = document.getElementById('pdfPreviewModal');
                    if (modal) {
                        modal.remove();
                        // Revoke the object URL to free memory
                        URL.revokeObjectURL(pdfUrl);
                    }
                }
            });
        };
        }, 2000); // 2 seconds delay
    });
});
</script>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header" style="background-color:#1e6031; color:white;">
        <h5 class="modal-title fw-bold mb-0 flex-grow-1">Add Vehicle</h5>
      </div>

      <!-- Form -->
      <form id="addVehicleForm" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body px-3 py-2">

          <!-- Brand & Model, Type Row -->
          <div class="row mb-2">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Brand & Model <span class="text-danger">*</span></label>
              <input type="text" name="model" id="modal_model" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Brand & Model" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Type <span class="text-danger">*</span></label>
              <input type="text" name="type" id="modal_type" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Type" required>
            </div>
          </div>

          <!-- Plate Number, Fuel Type Row -->
          <div class="row mb-2">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Plate Number <span class="text-danger">*</span></label>
              <input type="text" name="plate_number" id="modal_plate_number" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Plate Number" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Fuel Type <span class="text-danger">*</span></label>
              <input type="text" name="fuel_type" id="modal_fuel_type" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Fuel Type" required>
            </div>
          </div>

          <!-- Seating Capacity, Mileage Row -->
          <div class="row mb-2">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Seating Capacity <span class="text-danger">*</span></label>
              <input type="number" name="seating_capacity" id="modal_seating_capacity" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Seats" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Mileage (km) <span class="text-danger">*</span></label>
              <input type="number" name="mileage" id="modal_mileage" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Mileage" required>
            </div>
          </div>

          <!-- Picture Row -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Picture</label>
            <input type="file" name="picture" id="modal_picture" class="form-control form-control-sm py-1 px-2" style="font-size: 0.75rem; height: 30px; border: 1px solid #1e6031 !important; border-radius: 0.25rem !important;" accept="image/*">
            <style>
              #modal_picture:focus {
                border-color: #1e6031 !important;
                box-shadow: 0 0 0 0.2rem rgba(30, 96, 49, 0.25) !important;
                outline: 0;
              }
            </style>
          </div>
          
          <!-- Hidden Status (default: Available) -->
          <input type="hidden" name="status" value="Available">

        </div>

        <!-- Footer -->
       <div class="modal-footer py-1 justify-content-end">
  <button type="button" class="btn btn-sm btn-outline-secondary me-2 py-1" style="font-size: 0.75rem; height: 30px;" data-bs-dismiss="modal">
    Cancel
  </button>
  <button type="button" id="submitVehicleBtn" class="btn btn-sm btn-success py-1" style="font-size: 0.75rem; height: 30px;">
    Save
  </button>
</div>
      </form>
    </div>
  </div>
</div>

@endsection