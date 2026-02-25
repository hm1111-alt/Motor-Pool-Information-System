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
                    Division Management
                </h2>

                <!-- Right: Search Bar + Add Division Button -->
                <div class="flex items-center gap-2">
                    <!-- Search Bar -->
                    <form id="searchForm" method="GET" action="{{ route('admin.divisions.index') }}">
                        <div class="flex">
                            <input type="text" name="search" class="form-input" placeholder="Search divisions..." 
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
                    
                    <!-- Add New Division Button -->
                    <button type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#addDivisionModal"
                        class="inline-flex items-center px-3 py-1 bg-[#1e6031] border border-[#1e6031] rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150"
                        style="height: 32px;">
                        <svg class="mr-1" style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Division
                    </button>
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
<!-- Divisions Table -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead style="background-color: #1e6031; color: white;">
            <tr>
                <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 5%;">No.</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 30%;">Division Name</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 15%;">Abbreviation</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 12%;">Code</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 15%;">Status</th>
                <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 23%;">Actions</th>
            </tr>
        </thead>
        <tbody id="divisions-table-body">
            @include('admin.divisions.partials.table-body', ['divisions' => $divisions])
        </tbody>
    </table>
</div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2" id="pagination-container">
                    <div class="text-sm text-gray-600" id="pagination-info">
                        Showing {{ $divisions->firstItem() ?? 0 }} to {{ $divisions->lastItem() ?? 0 }} of {{ $divisions->total() }} results
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Custom Pagination - Copied from vehicles page -->
                        <nav>
                            <ul class="pagination flex items-center">
                                <li class="page-item {{ $divisions->currentPage() <= 1 ? 'disabled' : '' }}">
                                    <a class="page-link {{ $divisions->currentPage() <= 1 ? 'disabled-link' : '' }}" 
                                       href="{{ $divisions->currentPage() <= 1 ? 'javascript:void(0)' : $divisions->previousPageUrl() }}"
                                       {{ $divisions->currentPage() <= 1 ? 'aria-disabled="true" tabindex="-1"' : '' }}>Prev</a>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">{{ $divisions->currentPage() }}</span>
                                </li>
                                <li class="page-item {{ $divisions->currentPage() >= $divisions->lastPage() ? 'disabled' : '' }}">
                                    <a class="page-link {{ $divisions->currentPage() >= $divisions->lastPage() ? 'disabled-link' : '' }}" 
                                       href="{{ $divisions->currentPage() >= $divisions->lastPage() ? 'javascript:void(0)' : $divisions->nextPageUrl() }}"
                                       {{ $divisions->currentPage() >= $divisions->lastPage() ? 'aria-disabled="true" tabindex="-1"' : '' }}>Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ADD DIVISION MODAL -->
<div class="modal fade" id="addDivisionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header" style="background-color:#1e6031; color:white;">
                <h5 class="modal-title fw-bold">Add New Division</h5>
            </div>
            
            <!-- FORM -->
            <form method="POST" action="{{ route('admin.divisions.store') }}">
                @csrf
                <div class="modal-body px-3 py-2">
                    <!-- Office -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Office <span class="text-danger">*</span></label>
                        <select name="office_id" class="form-control form-control-sm border-success" required>
                            <option value="">Select Office</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Name -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Division Name <span class="text-danger">*</span></label>
                        <input type="text" name="division_name" class="form-control form-control-sm border-success" placeholder="Enter division name" required>
                    </div>
                    
                    <!-- Abbreviation -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Abbreviation <span class="text-danger">*</span></label>
                        <input type="text" name="division_abbr" class="form-control form-control-sm border-success" placeholder="Enter abbreviation (e.g., HRD, FMD)" required>
                    </div>
                    
                    <!-- Code -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Division Code <span class="text-danger">*</span></label>
                        <input type="text" name="division_code" class="form-control form-control-sm border-success" placeholder="Enter division code" required>
                    </div>
                    
                    <!-- Hidden Status Field (always Active) -->
                    <input type="hidden" name="division_isactive" value="1">
                </div>
                
                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addDivisionCancelButton">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">Create Division</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT DIVISION MODAL -->
<div class="modal fade" id="editDivisionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header" style="background-color:#1e6031; color:white;">
                <h5 class="modal-title fw-bold">Edit Division</h5>
            </div>
            
            <!-- FORM -->
            <form method="POST" id="editDivisionForm">
                @csrf
                @method('PUT')
                <div class="modal-body px-3 py-2">
                    <!-- Office -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Office <span class="text-danger">*</span></label>
                        <select name="office_id" id="edit_office_id" class="form-control form-control-sm border-success" required>
                            <option value="">Select Office</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Name -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Division Name <span class="text-danger">*</span></label>
                        <input type="text" name="division_name" id="edit_division_name" class="form-control form-control-sm border-success" placeholder="Enter division name" required>
                    </div>
                    
                    <!-- Abbreviation -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Abbreviation <span class="text-danger">*</span></label>
                        <input type="text" name="division_abbr" id="edit_division_abbreviation" class="form-control form-control-sm border-success" placeholder="Enter abbreviation (e.g., HRD, FMD)" required>
                    </div>
                    
                    <!-- Code -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Division Code <span class="text-danger">*</span></label>
                        <input type="text" name="division_code" id="edit_division_code" class="form-control form-control-sm border-success" placeholder="Enter division code" required>
                    </div>
                    
                    <!-- Status -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Status <span class="text-danger">*</span></label>
                        <select name="division_isactive" id="edit_division_status" class="form-control form-control-sm border-success" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="editDivisionCancelButton">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">Update Division</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* Action buttons styling to match offices page */
.action-buttons .btn {
    font-size: 10px;
    padding: 2px 6px;
    line-height: 1;
    height: 25px;
    min-width: 50px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
    border-radius: 4px;
}

.action-buttons .btn i,
.action-buttons .btn svg {
    font-size: 10px;
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

.page-item {
    margin: 0 2px;
}
</style>

<!-- SweetAlert2 for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Display success/error messages from session
    @if(session('success'))
    Swal.fire({
        title: 'Success!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#1e6031'
    });
    @endif
    
    @if(session('error'))
    Swal.fire({
        title: 'Error!',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonColor: '#1e6031'
    });
    @endif
    
    // Handle modal form clearing
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
                    window.location.href = '{{ route("admin.divisions.index") }}';
                    e.preventDefault();
                }
            });
        }
        
        // Handle edit modal opening
        const editModal = document.getElementById('editDivisionModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const abbreviation = button.getAttribute('data-abbreviation');
            const code = button.getAttribute('data-code');
            const status = button.getAttribute('data-status');
            const officeId = button.getAttribute('data-office-id');
            
            // Store original data (database values)
            editModal.originalData = {
                name: name,
                abbreviation: abbreviation,
                code: code,
                status: status,
                officeId: officeId
            };
            
            // Check if we have stored edited data from a previous session (closed via backdrop/X)
            if (editModal.editedData) {
                // Use previously edited data if modal was closed without canceling
                document.getElementById('edit_division_name').value = editModal.editedData.name;
                document.getElementById('edit_division_abbreviation').value = editModal.editedData.abbreviation;
                document.getElementById('edit_division_code').value = editModal.editedData.code;
                document.getElementById('edit_division_status').value = editModal.editedData.status;
                document.getElementById('edit_office_id').value = editModal.editedData.officeId;
            } else {
                // Otherwise use original data
                document.getElementById('edit_division_name').value = name;
                document.getElementById('edit_division_abbreviation').value = abbreviation;
                document.getElementById('edit_division_code').value = code;
                document.getElementById('edit_division_status').value = status;
                document.getElementById('edit_office_id').value = officeId;
            }
            
            // Update form action
            document.getElementById('editDivisionForm').action = `/admin/divisions/${id}`;
        });
        
        // Handle Add Division modal cancel button
        const addCancelButton = document.getElementById('addDivisionCancelButton');
        const addForm = document.querySelector('#addDivisionModal form');
        
        if (addCancelButton && addForm) {
            addCancelButton.addEventListener('click', function() {
                // Clear all form fields
                addForm.reset();
                
                // Remove any validation error classes
                addForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                addForm.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
                
                // Close the modal using Bootstrap
                const addModal = document.getElementById('addDivisionModal');
                const modalInstance = bootstrap.Modal.getInstance(addModal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
        }
        
        // Handle Edit Division modal cancel button - resets to original data
        const editCancelButton = document.getElementById('editDivisionCancelButton');
        const editForm = document.querySelector('#editDivisionForm');
        
        if (editCancelButton && editForm) {
            editCancelButton.addEventListener('click', function() {
                // Reset form to original values (before any edits)
                if (editModal.originalData) {
                    document.getElementById('edit_division_name').value = editModal.originalData.name;
                    document.getElementById('edit_division_abbreviation').value = editModal.originalData.abbreviation;
                    document.getElementById('edit_division_code').value = editModal.originalData.code;
                    document.getElementById('edit_division_status').value = editModal.originalData.status;
                    document.getElementById('edit_office_id').value = editModal.originalData.officeId;
                }
                
                // Remove any validation error classes
                editForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                editForm.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
                
                // Clear the stored edited data since user cancelled
                editModal.editedData = null;
                
                // Close the modal using Bootstrap
                const modalInstance = bootstrap.Modal.getInstance(editModal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
        }
        
        // Handle modal closing events (click outside or X button) - preserve edited data
        editModal.addEventListener('hide.bs.modal', function() {
            // Store current form data when modal is closed via backdrop or X button
            editModal.editedData = {
                name: document.getElementById('edit_division_name').value,
                abbreviation: document.getElementById('edit_division_abbreviation').value,
                code: document.getElementById('edit_division_code').value,
                status: document.getElementById('edit_division_status').value,
                officeId: document.getElementById('edit_office_id').value
            };
        });
        
        // Handle form submission with AJAX
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // First show loading alert for 2 seconds
            Swal.fire({
                title: 'Creating Division...',
                text: 'Please wait while we process your request',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                timer: 2000,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // After 2 seconds, show success message that auto-dismisses
            setTimeout(() => {
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            didOpen: () => {
                                // Close modal immediately when success alert opens
                                const addModal = document.getElementById('addDivisionModal');
                                const modalInstance = bootstrap.Modal.getInstance(addModal);
                                if (modalInstance) {
                                    modalInstance.hide();
                                }
                            }
                        }).then(() => {
                            // Refresh page after success message disappears
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'An error occurred.',
                            icon: 'error',
                            confirmButtonColor: '#1e6031'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while submitting the form.',
                        icon: 'error',
                        confirmButtonColor: '#1e6031'
                    });
                });
            }, 2000);
        });
        
        // Handle edit form submission with AJAX
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // First show loading alert for 2 seconds
            Swal.fire({
                title: 'Updating Division...',
                text: 'Please wait while we process your request',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                timer: 2000,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // After 2 seconds, show success message that auto-dismisses
            setTimeout(() => {
                const formData = new FormData(this);
                // Add the PUT method override for the update form
                formData.append('_method', 'PUT');
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            didOpen: () => {
                                // Close modal immediately when success alert opens
                                const editModal = document.getElementById('editDivisionModal');
                                const modalInstance = bootstrap.Modal.getInstance(editModal);
                                if (modalInstance) {
                                    modalInstance.hide();
                                }
                            }
                        }).then(() => {
                            // Refresh page after success message disappears
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'An error occurred.',
                            icon: 'error',
                            confirmButtonColor: '#1e6031'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while submitting the form.',
                        icon: 'error',
                        confirmButtonColor: '#1e6031'
                    });
                });
            }, 2000);
        });
    });
</script>
</x-admin-layout>