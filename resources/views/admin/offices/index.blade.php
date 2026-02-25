<x-admin-layout>
    <x-slot name="header">
        <!-- CSRF Token for AJAX requests -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="flex justify-center items-center">
            <!-- Centered Content -->
            <div class="flex justify-between items-center" style="width: 100%; max-width: 1200px;">
                <!-- Left: Heading -->
                <h2 class="font-bold flex items-center" style="color: #1e6031; font-size: 1.5rem; height: 32px; margin-top: 10px;">
                    <svg class="mr-2" style="width: 24px; height: 24px; color: #1e6031;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Office Management
                </h2>

                <!-- Right: Search Bar + Add Office Button -->
                <div class="flex items-center gap-2">
                    <!-- Search Bar -->
                    <form id="searchForm" method="GET" action="{{ route('admin.offices.index') }}">
                        <div class="flex">
                            <input type="text" name="search" class="form-input" placeholder="Search offices..." 
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
                    
                    <!-- Add New Office Button (NOW OPENS MODAL) -->
                    <button type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#addOfficeModal"
                        class="inline-flex items-center px-3 py-1 bg-[#1e6031] border border-[#1e6031] rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150"
                        style="height: 32px;">
                        <svg class="mr-1" style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Office
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
<!-- Offices Table -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead style="background-color: #1e6031; color: white;">
            <tr>
                <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 5%;">No.</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 35%;">Office Name</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 15%;">Abbreviation</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 15%;">Code</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 15%;">Status</th>
                <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 15%;">Actions</th>
            </tr>
        </thead>
        <tbody id="offices-table-body">
            @include('admin.offices.partials.table-body', ['offices' => $offices])
        </tbody>
    </table>
</div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2" id="pagination-container">
                    <div class="text-sm text-gray-600" id="pagination-info">
                        Showing {{ $offices->firstItem() ?? 0 }} to {{ $offices->lastItem() ?? 0 }} of {{ $offices->total() }} results
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Custom Pagination - Copied from vehicles page -->
                        <nav>
                            <ul class="pagination flex items-center">
                                <li class="page-item {{ $offices->currentPage() <= 1 ? 'disabled' : '' }}">
                                    <a class="page-link {{ $offices->currentPage() <= 1 ? 'disabled-link' : '' }}" 
                                       href="{{ $offices->currentPage() <= 1 ? 'javascript:void(0)' : $offices->previousPageUrl() }}"
                                       {{ $offices->currentPage() <= 1 ? 'aria-disabled="true" tabindex="-1"' : '' }}>Prev</a>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">{{ $offices->currentPage() }}</span>
                                </li>
                                <li class="page-item {{ $offices->currentPage() >= $offices->lastPage() ? 'disabled' : '' }}">
                                    <a class="page-link {{ $offices->currentPage() >= $offices->lastPage() ? 'disabled-link' : '' }}" 
                                       href="{{ $offices->currentPage() >= $offices->lastPage() ? 'javascript:void(0)' : $offices->nextPageUrl() }}"
                                       {{ $offices->currentPage() >= $offices->lastPage() ? 'aria-disabled="true" tabindex="-1"' : '' }}>Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
/* Action buttons styling to match vehicles page */
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
                window.location.href = '{{ route("admin.offices.index") }}';
                e.preventDefault();
            }
        });
    }
});
</script>
</x-admin-layout>

<!-- ADD OFFICE MODAL -->
<div class="modal fade" id="addOfficeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header" style="background-color:#1e6031; color:white;">
                <h5 class="modal-title fw-bold">Add New Office</h5>
            </div>
            
            <!-- FORM -->
            <form method="POST" action="{{ route('admin.offices.store') }}">
                @csrf
                <div class="modal-body px-3 py-2">
                    <!-- Name -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Office Name <span class="text-danger">*</span></label>
                        <input type="text" name="office_name" class="form-control form-control-sm border-success" placeholder="Enter office name" required>
                    </div>
                    
                    <!-- Program -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Office Program <span class="text-danger">*</span></label>
                        <input type="text" name="program" class="form-control form-control-sm border-success" placeholder="Enter office program" required>
                    </div>
                    
                    <!-- Abbreviation -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Abbreviation <span class="text-danger">*</span></label>
                        <input type="text" name="abbreviation" class="form-control form-control-sm border-success" placeholder="Enter abbreviation (e.g., HRMO, FMIS)" required>
                    </div>
                    
                    <!-- Code -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Office Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control form-control-sm border-success" placeholder="Enter office code" required>
                    </div>
                    
                    <!-- Hidden Status Field (always Active) -->
                    <input type="hidden" name="status" value="Active">
                </div>
                
                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="cancelButton">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">Create Office</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT OFFICE MODAL -->
<div class="modal fade" id="editOfficeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header" style="background-color:#1e6031; color:white;">
                <h5 class="modal-title fw-bold">Edit Office</h5>
            </div>
            
            <!-- FORM -->
            <form method="POST" id="editOfficeForm">
                @csrf
                @method('PUT')
                <div class="modal-body px-3 py-2">
                    <!-- Name -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Office Name <span class="text-danger">*</span></label>
                        <input type="text" name="office_name" id="edit_office_name" class="form-control form-control-sm border-success" placeholder="Enter office name" required>
                    </div>
                    
                    <!-- Program -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Office Program <span class="text-danger">*</span></label>
                        <input type="text" name="program" id="edit_program" class="form-control form-control-sm border-success" placeholder="Enter office program" required>
                    </div>
                    
                    <!-- Abbreviation -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Abbreviation <span class="text-danger">*</span></label>
                        <input type="text" name="abbreviation" id="edit_abbreviation" class="form-control form-control-sm border-success" placeholder="Enter abbreviation (e.g., HRMO, FMIS)" required>
                    </div>
                    
                    <!-- Code -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Office Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="edit_code" class="form-control form-control-sm border-success" placeholder="Enter office code" required>
                    </div>
                    
                    <!-- Status -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Status <span class="text-danger">*</span></label>
                        <select name="status" id="edit_status" class="form-control form-control-sm border-success" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="editCancelButton">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">Update Office</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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
        const modal = document.getElementById('addOfficeModal');
        const editModal = document.getElementById('editOfficeModal');
        const form = modal.querySelector('form');
        const editForm = editModal.querySelector('form');
        const cancelBtn = document.getElementById('cancelButton');
        
        // Store original form data when modal opens
        let originalFormData = new FormData();
        let shouldClearForm = false;
        
        // When modal is shown, save current form state
        modal.addEventListener('show.bs.modal', function() {
            // Save current form data
            const formData = new FormData(form);
            originalFormData = new FormData();
            for (let [key, value] of formData.entries()) {
                originalFormData.append(key, value);
            }
            shouldClearForm = false; // Reset the flag
        });
        
        // Handle Cancel button click
        cancelBtn.addEventListener('click', function() {
            // Clear the form completely
            form.reset();
            // Also clear any validation errors
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            // Close the modal
            bootstrap.Modal.getInstance(modal).hide();
        });
        
        // Handle backdrop click (preserve data)
        modal.addEventListener('hide.bs.modal', function(event) {
            // Only preserve data if it's a backdrop click (not cancel button)
            // The cancel button handles its own closing
        });
        
        // Handle edit modal opening
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const id = button.getAttribute('data-id');
            const program = button.getAttribute('data-program');
            const name = button.getAttribute('data-name');
            const abbreviation = button.getAttribute('data-abbreviation');
            const code = button.getAttribute('data-code');
            const status = button.getAttribute('data-status');
            
            console.log('Modal opening - resetToOriginal flag:', editModal.resetToOriginal);
            console.log('Modal opening - currentData exists:', !!editModal.currentData);
            
            // Store original data (database values)
            editModal.originalData = {
                program: program,
                name: name,
                abbreviation: abbreviation,
                code: code,
                status: status
            };
            
            // Check if we have saved current state from previous editing
            if (editModal.currentData && !editModal.resetToOriginal) {
                console.log('Using saved current data');
                document.getElementById('edit_program').value = editModal.currentData.program;
                document.getElementById('edit_office_name').value = editModal.currentData.name;
                document.getElementById('edit_abbreviation').value = editModal.currentData.abbreviation;
                document.getElementById('edit_code').value = editModal.currentData.code;
                document.getElementById('edit_status').value = editModal.currentData.status;
            } else {
                console.log('Using original data');
                // Use original data (first open or after cancel)
                document.getElementById('edit_office_name').value = name;
                document.getElementById('edit_program').value = program;
                document.getElementById('edit_abbreviation').value = abbreviation;
                document.getElementById('edit_code').value = code;
                document.getElementById('edit_status').value = status;
                // Reset flags
                editModal.resetToOriginal = false;
            }
            
            // Update form action
            editForm.action = `/admin/offices/${id}`;
        });
        
        // Detect backdrop click
        editModal.addEventListener('click', function(event) {
            // If click is directly on the modal backdrop (not the content)
            if (event.target === editModal) {
                // Save current form state
                editModal.currentData = {
                    program: document.getElementById('edit_program').value,
                    name: document.getElementById('edit_office_name').value,
                    abbreviation: document.getElementById('edit_abbreviation').value,
                    code: document.getElementById('edit_code').value,
                    status: document.getElementById('edit_status').value
                };
                editModal.backdropClicked = true;
            }
        });
        
        // Handle cancel button in edit modal
        const editCancelBtn = document.getElementById('editCancelButton');
        if (editCancelBtn) {
            editCancelBtn.addEventListener('click', function() {
                console.log('Cancel button clicked');
                console.log('backdropClicked:', editModal.backdropClicked);
                console.log('originalData exists:', !!editModal.originalData);
                
                // Always reset to original data when cancel is clicked
                if (editModal.originalData) {
                    console.log('Resetting form to original data');
                    document.getElementById('edit_office_name').value = editModal.originalData.name;
                    document.getElementById('edit_program').value = editModal.originalData.program;
                    document.getElementById('edit_abbreviation').value = editModal.originalData.abbreviation;
                    document.getElementById('edit_code').value = editModal.originalData.code;
                    document.getElementById('edit_status').value = editModal.originalData.status;
                    
                    // Clear current data and set reset flag
                    editModal.currentData = null;
                    editModal.resetToOriginal = true;
                    editModal.backdropClicked = false;
                }
                
                // Close modal
                bootstrap.Modal.getInstance(editModal).hide();
            });
        }
        
        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Form submission started');
            
            // Perform form validation
            const formData = new FormData(form);
            const requiredFields = ['program', 'office_name', 'abbreviation', 'code'];
            let isValid = true;
            
            // Clear previous validation errors
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            
            // Check required fields
            requiredFields.forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback d-block';
                    errorDiv.textContent = 'This field is required.';
                    field.parentNode.appendChild(errorDiv);
                    isValid = false;
                }
            });
            
            if (isValid) {
                console.log('Form is valid, submitting...');
                
                // Submit form via AJAX
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success response:', data);
                    if (data.success) {
                        // First show loading alert for 2 seconds with proper loading spinner
                        Swal.fire({
                            title: 'Creating Office...',
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
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
               
                                didOpen: () => {
                                    // Close modal immediately when success alert opens
                                    bootstrap.Modal.getInstance(modal).hide();
                                }
                            }).then(() => {
                                // Refresh page after success message disappears
                                location.reload();
                            });
                        }, 2000);
                    } else {
                        // Show error message
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'An error occurred.',
                            icon: 'error',
                            confirmButtonColor: '#1e6031'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error details:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while submitting the form: ' + error.message,
                        icon: 'error',
                        confirmButtonColor: '#1e6031'
                    });
                });
            } else {
                console.log('Form validation failed');
            }
        });
        
        // Handle edit form submission
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Edit form submission started');
            
            // Perform form validation
            const formData = new FormData(editForm);
            const requiredFields = ['program', 'office_name', 'abbreviation', 'code', 'status'];
            let isValid = true;
            
            // Clear previous validation errors
            editForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            editForm.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            
            // Check required fields
            requiredFields.forEach(fieldName => {
                const field = editForm.querySelector(`[name="${fieldName}"]`);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback d-block';
                    errorDiv.textContent = 'This field is required.';
                    field.parentNode.appendChild(errorDiv);
                    isValid = false;
                }
            });
            
            if (isValid) {
                console.log('Edit form is valid, submitting...');
                
                // Submit form via AJAX
                fetch(editForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success response:', data);
                    if (data.success) {
                        // First show loading alert for 2 seconds with proper loading spinner
                        Swal.fire({
                            title: 'Updating Office...',
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
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                didOpen: () => {
                                    // Close modal immediately when success alert opens
                                    bootstrap.Modal.getInstance(editModal).hide();
                                }
                            }).then(() => {
                                // Refresh page after success message disappears
                                location.reload();
                            });
                        }, 2000);
                    } else {
                        // Show error message
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'An error occurred.',
                            icon: 'error',
                            confirmButtonColor: '#1e6031'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error details:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while submitting the form: ' + error.message,
                        icon: 'error',
                        confirmButtonColor: '#1e6031'
                    });
                });
            } else {
                console.log('Edit form validation failed');
            }
        });
    });
    

</script>
