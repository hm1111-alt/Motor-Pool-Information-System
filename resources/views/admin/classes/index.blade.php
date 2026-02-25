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
                    Class Management
                </h2>

                <!-- Right: Search Bar + Add Class Button -->
                <div class="flex items-center gap-2">
                    <!-- Search Bar -->
                    <form id="searchForm" method="GET" action="{{ route('admin.classes.index') }}">
                        <div class="flex">
                            <input type="text" name="search" class="form-input" placeholder="Search classes..." 
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
                    
                    <!-- Add New Class Button (NOW OPENS MODAL) -->
                    <button type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#addClassModal"
                        class="inline-flex items-center px-3 py-1 bg-[#1e6031] border border-[#1e6031] rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150"
                        style="height: 32px;">
                        <svg class="mr-1" style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Class
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

<div class="-mt-5">
    <div class="flex justify-center">
        <div style="width: 100%; max-width: 1200px;">
<!-- Classes Table -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead style="background-color: #1e6031; color: white;">
            <tr>
                <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 8%;">No.</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 72%;">Class Name</th>
                <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 20%;">Actions</th>
            </tr>
        </thead>
        <tbody id="classes-table-body">
            @include('admin.classes.partials.table-body')
        </tbody>
    </table>
</div>

                <!-- Pagination -->
                <div class="flex justify-between items-center mt-4" id="pagination-container">
                    <div class="text-sm text-gray-600" id="pagination-info">
                        Showing {{ $classes->firstItem() ?? 0 }} to {{ $classes->lastItem() ?? 0 }} of {{ $classes->total() }} results
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Custom Pagination - Copied from vehicles page -->
                        <nav>
                            <ul class="pagination">
                                <li class="page-item {{ $classes->currentPage() <= 1 ? 'disabled' : '' }}">
                                    <a class="page-link {{ $classes->currentPage() <= 1 ? 'disabled-link' : '' }}" 
                                       href="{{ $classes->currentPage() <= 1 ? 'javascript:void(0)' : $classes->previousPageUrl() }}"
                                       {{ $classes->currentPage() <= 1 ? 'aria-disabled="true" tabindex="-1"' : '' }}>Prev</a>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">{{ $classes->currentPage() }}</span>
                                </li>
                                <li class="page-item {{ $classes->currentPage() >= $classes->lastPage() ? 'disabled' : '' }}">
                                    <a class="page-link {{ $classes->currentPage() >= $classes->lastPage() ? 'disabled-link' : '' }}" 
                                       href="{{ $classes->currentPage() >= $classes->lastPage() ? 'javascript:void(0)' : $classes->nextPageUrl() }}"
                                       {{ $classes->currentPage() >= $classes->lastPage() ? 'aria-disabled="true" tabindex="-1"' : '' }}>Next</a>
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

/* CUSTOM MODAL BACKDROP - Gray like office section */
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5) !important;
}

.modal-backdrop.show {
    opacity: 0.5 !important;
}
</style>

<!-- ADD CLASS MODAL -->
<div class="modal fade" id="addClassModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header" style="background-color:#1e6031; color:white;">
                <h5 class="modal-title fw-bold">Add New Class</h5>
            </div>
            
            <!-- FORM -->
            <form method="POST" action="{{ route('admin.classes.store') }}">
                @csrf
                <div class="modal-body px-3 py-2">
                    <!-- Name -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Class Name <span class="text-danger">*</span></label>
                        <input type="text" name="class_name" class="form-control form-control-sm border-success" placeholder="Enter class name" required>
                    </div>
                </div>
                
                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="cancelButton">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">Create Class</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT CLASS MODAL -->
<div class="modal fade" id="editClassModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header" style="background-color:#1e6031; color:white;">
                <h5 class="modal-title fw-bold">Edit Class</h5>
            </div>
            
            <!-- FORM -->
            <form method="POST" id="editClassForm">
                @csrf
                @method('PUT')
                <div class="modal-body px-3 py-2">
                    <!-- Name -->
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Class Name <span class="text-danger">*</span></label>
                        <input type="text" name="class_name" id="edit_class_name" class="form-control form-control-sm border-success" placeholder="Enter class name" required>
                    </div>
                </div>
                
                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="editCancelButton">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">Update Class</button>
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
        const modal = document.getElementById('addClassModal');
        const editModal = document.getElementById('editClassModal');
        const form = modal.querySelector('form');
        const editForm = editModal.querySelector('form');
        const cancelBtn = document.getElementById('cancelButton');
        
        // GLOBAL CLEANUP FUNCTION - Run periodically to prevent artifacts
        const globalCleanup = () => {
            // Remove any orphaned backdrop elements
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                if (!document.querySelector('.modal.show')) {
                    backdrop.remove();
                }
            });
            
            // Remove modal-open class if no modals are showing
            if (!document.querySelector('.modal.show')) {
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }
        };
        
        // Run global cleanup every 2 seconds
        setInterval(globalCleanup, 2000);
        
        // Also run on page visibility change
        document.addEventListener('visibilitychange', globalCleanup);
        
        // Store original form data when modal opens
        let originalFormData = new FormData();
        let shouldClearForm = false;
        
        // When modal is shown, save current form state
        modal.addEventListener('show.bs.modal', function() {
            // NUCLEAR CLEANUP - Remove ALL possible Bootstrap artifacts
            // This runs BEFORE showing the modal to prevent stacking issues
            const cleanUpEverything = () => {
                // Remove all backdrop elements (including duplicates)
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                    backdrop.remove();
                });
                
                // Remove all modal show classes
                document.querySelectorAll('.modal.show, .modal.in, .modal.fade.show').forEach(modalEl => {
                    modalEl.classList.remove('show', 'in');
                    modalEl.style.display = 'none';
                });
                
                // Clean up body classes and styles
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                
                // Force garbage collection of modal-related elements
                const modalElements = document.querySelectorAll('[class*="modal"]');
                modalElements.forEach(el => {
                    if (el.classList.length === 0) {
                        el.remove();
                    }
                });
            };
            
            // Run cleanup immediately
            cleanUpEverything();
            
            // Run cleanup again after a tiny delay to catch any delayed artifacts
            setTimeout(cleanUpEverything, 10);
            
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
            
            // COMPLETE MODAL CLEANUP - EVERY TIME
            try {
                // Method 1: Try Bootstrap's hide method
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
                
                // Method 2: Aggressive cleanup (always run)
                // Remove ALL backdrop elements immediately
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                    backdrop.remove();
                });
                
                // Remove all modal classes and styles
                modal.classList.remove('show', 'in');
                modal.style.display = 'none';
                
                // Clean up body
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                
                // Remove any duplicate modal elements
                document.querySelectorAll('.modal').forEach(modalEl => {
                    if (modalEl !== modal) {
                        modalEl.remove();
                    }
                });
                
                // Force reflow to ensure clean state
                document.body.offsetHeight;
                
            } catch (error) {
                console.error('Error closing modal:', error);
                // Emergency cleanup
                modal.classList.remove('show');
                modal.style.display = 'none';
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }
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
            const name = button.getAttribute('data-name');
            
            console.log('Modal opening - resetToOriginal flag:', editModal.resetToOriginal);
            console.log('Modal opening - currentData exists:', !!editModal.currentData);
            
            // NUCLEAR CLEANUP - Remove ALL possible Bootstrap artifacts
            // This runs BEFORE showing the modal to prevent stacking issues
            const cleanUpEverything = () => {
                // Remove all backdrop elements (including duplicates)
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                    backdrop.remove();
                });
                
                // Remove all modal show classes
                document.querySelectorAll('.modal.show, .modal.in, .modal.fade.show').forEach(modalEl => {
                    modalEl.classList.remove('show', 'in');
                    modalEl.style.display = 'none';
                });
                
                // Clean up body classes and styles
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                
                // Force garbage collection of modal-related elements
                const modalElements = document.querySelectorAll('[class*="modal"]');
                modalElements.forEach(el => {
                    if (el.classList.length === 0) {
                        el.remove();
                    }
                });
            };
            
            // Run cleanup immediately
            cleanUpEverything();
            
            // Run cleanup again after a tiny delay to catch any delayed artifacts
            setTimeout(cleanUpEverything, 10);
            
            // Store original data (database values)
            editModal.originalData = {
                name: name
            };
            
            // Check if we have saved current state from previous editing
            if (editModal.currentData && !editModal.resetToOriginal) {
                console.log('Using SAVED USER DATA (from backdrop click)');
                document.getElementById('edit_class_name').value = editModal.currentData.name;
            } else {
                console.log('Using ORIGINAL DATABASE VALUES (first open or after cancel)');
                // Use original data (first open or after cancel)
                document.getElementById('edit_class_name').value = name;
                // Reset flags
                editModal.resetToOriginal = false;
            }
            
            // Update form action
            editForm.action = `/admin/classes/${id}`;
        });
        
        // Detect backdrop click
        editModal.addEventListener('click', function(event) {
            // If click is directly on the modal backdrop (not the content)
            if (event.target === editModal) {
                // Save current form state
                editModal.currentData = {
                    name: document.getElementById('edit_class_name').value
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
                
                // ALWAYS reset to original database data when cancel is clicked (explicitly clear any user changes)
                if (editModal.originalData) {
                    console.log('CANCEL CLICKED - Resetting form to ORIGINAL DATABASE VALUES');
                    document.getElementById('edit_class_name').value = editModal.originalData.name;
                    
                    // Clear any current user data and set flags to force original data on next open
                    editModal.currentData = null;        // Remove saved user data
                    editModal.resetToOriginal = true;    // Force use of original data next time
                    editModal.backdropClicked = false;   // Reset backdrop flag
                }
                
                // COMPLETE MODAL CLEANUP - EVERY TIME
                try {
                    // Method 1: Try Bootstrap's hide method
                    const editModalInstance = bootstrap.Modal.getInstance(editModal);
                    if (editModalInstance) {
                        editModalInstance.hide();
                    }
                    
                    // Method 2: Aggressive cleanup (always run)
                    // Remove ALL backdrop elements immediately
                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                        backdrop.remove();
                    });
                    
                    // Remove all modal classes and styles
                    editModal.classList.remove('show', 'in');
                    editModal.style.display = 'none';
                    
                    // Clean up body
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                    
                    // Remove any duplicate modal elements
                    document.querySelectorAll('.modal').forEach(modalEl => {
                        if (modalEl !== editModal) {
                            modalEl.remove();
                        }
                    });
                    
                    // Force reflow to ensure clean state
                    document.body.offsetHeight;
                    
                } catch (error) {
                    console.error('Error closing modal:', error);
                    // Emergency cleanup
                    editModal.classList.remove('show');
                    editModal.style.display = 'none';
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }
            });
        }
        
        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Form submission started');
            
            // Perform form validation
            const formData = new FormData(form);
            const requiredFields = ['class_name'];
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
                            title: 'Creating Class...',
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
                                    const modalInstance = bootstrap.Modal.getInstance(modal);
                                    if (modalInstance) {
                                        modalInstance.hide();
                                    } else {
                                        // Emergency cleanup
                                        modal.classList.remove('show');
                                        modal.style.display = 'none';
                                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                                        document.body.classList.remove('modal-open');
                                        document.body.style.overflow = '';
                                        document.body.style.paddingRight = '';
                                    }
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
            const requiredFields = ['class_name'];
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
                            title: 'Updating Class...',
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
                                    const editModalInstance = bootstrap.Modal.getInstance(editModal);
                                    if (editModalInstance) {
                                        editModalInstance.hide();
                                    } else {
                                        // Fallback: manually remove modal and backdrop
                                        editModal.classList.remove('show');
                                        editModal.style.display = 'none';
                                        document.querySelector('.modal-backdrop')?.remove();
                                        document.body.classList.remove('modal-open');
                                        document.body.style.overflow = '';
                                        document.body.style.paddingRight = '';
                                    }
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
</x-admin-layout>