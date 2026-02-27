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

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

/* CUSTOM MODAL BACKDROP - Darker for better visibility */
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.7) !important;
}

.modal-backdrop.show {
    opacity: 0.7 !important;
}

/* Enhanced modal styling for better visual separation */
.modal-content {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3) !important;
    border: none !important;
}
</style>

<!-- ADD CLASS MODAL -->
<div class="modal fade" id="addClassModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
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
        <div class="modal-content" style="border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addModal = document.getElementById('addClassModal');
    const editModal = document.getElementById('editClassModal');
    const addForm = addModal.querySelector('form');
    const editForm = editModal.querySelector('form');
    const cancelButton = document.getElementById('cancelButton');
    const editCancelButton = document.getElementById('editCancelButton');

    // Storage for modal state preservation
    let addModalStoredData = {};
    let editModalOriginalData = {};
    let editModalCurrentData = {};
    let currentEditingId = null;

    // Utility: show validation errors inside the form
    function displayErrors(form, errors) {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        for (const field in errors) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                errors[field].forEach(msg => {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback d-block';
                    errorDiv.textContent = msg;
                    input.parentNode.appendChild(errorDiv);
                });
            }
        }
    }

    // --- ADD MODAL LOGIC ---
    addModal.addEventListener('show.bs.modal', function() {
        if (addModalStoredData.class_name) {
            document.querySelector('[name="class_name"]').value = addModalStoredData.class_name;
        } else {
            addForm.reset();
            document.querySelector('[name="class_name"]').value = '';
        }
    });

    addModal.addEventListener('mousedown', function(event) {
        if (event.target === addModal) {
            addModalStoredData = {
                class_name: document.querySelector('[name="class_name"]').value
            };
        }
    });

    cancelButton.addEventListener('click', function() {
        addModalStoredData = {};
        addForm.reset();
        document.querySelector('[name="class_name"]').value = '';
        addForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        addForm.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        bootstrap.Modal.getInstance(addModal)?.hide();
    });

    // --- EDIT MODAL LOGIC ---
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');

        editModalOriginalData = { class_name: name };

        if (editModalCurrentData.class_name && currentEditingId === id) {
            document.getElementById('edit_class_name').value = editModalCurrentData.class_name;
        } else {
            document.getElementById('edit_class_name').value = name;
            editModalCurrentData = {...editModalOriginalData};
        }

        currentEditingId = id;
        editForm.action = `/admin/classes/${id}`;
    });

    editModal.addEventListener('mousedown', function(event) {
        if (event.target === editModal) {
            editModalCurrentData = {
                class_name: document.getElementById('edit_class_name').value
            };
        }
    });

    editCancelButton.addEventListener('click', function() {
        document.getElementById('edit_class_name').value = editModalOriginalData.class_name;
        editModalCurrentData = {...editModalOriginalData};
        editForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        editForm.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        bootstrap.Modal.getInstance(editModal)?.hide();
    });

    // AJAX form submission with 2-second loading after button click
    function handleFormSubmit(modalEl, form, loadingTitle) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            const formData = new FormData(form);
            fetch(form.action, {
                method: form.method || 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw data;
                return data;
            })
            .then(data => {
                // Close modal first
                bootstrap.Modal.getInstance(modalEl)?.hide();

                // Show 2-second loading with original text
                Swal.fire({
                    title: loadingTitle,
                    text: 'Please wait while we process your request',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 2000,
                    didOpen: () => Swal.showLoading()
                }).then(() => {
                    // Show success message after 2 seconds
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                });
            })
            .catch(err => {
                if (err.errors) {
                    displayErrors(form, err.errors);
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: err.message || 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonColor: '#1e6031'
                    });
                }
            });
        });
    }

    handleFormSubmit(addModal, addForm, 'Creating Class...');
    handleFormSubmit(editModal, editForm, 'Updating Class...');
});
</script>
</x-admin-layout>