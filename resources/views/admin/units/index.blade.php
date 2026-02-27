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
                    Unit Management
                </h2>

                <!-- Right: Search Bar + Add Unit Button -->
                <div class="flex items-center gap-2">
                    <!-- Search Bar -->
                    <form id="searchForm" method="GET" action="{{ route('admin.units.index') }}">
                        <div class="flex">
                            <input type="text" name="search" class="form-input" placeholder="Search units..." 
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
                    
                    <!-- Add New Unit Button (Modal Trigger) -->
                    <button type="button" id="addUnitBtn" class="inline-flex items-center px-3 py-1 bg-[#1e6031] border border-[#1e6031] rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150" style="height: 32px;">
                        Add New Unit
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

<!-- Units Table -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200" style="font-size: 0.85rem;">
        <thead style="background-color: #1e6031; color: white;">
            <tr>
                <th style="padding: 8px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; width: 5%;">No.</th>
                <th style="padding: 8px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; width: 30%;">Unit Name</th>
                <th style="padding: 8px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; width: 12%;">Abbreviation</th>
                <th style="padding: 8px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; width: 12%;">Code</th>
                <th style="padding: 8px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; width: 12%;">Status</th>
                <th style="padding: 8px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; width: 15%;">Actions</th>
            </tr>
        </thead>
        <tbody id="units-table-body">
            @include('admin.units.partials.table-body')
        </tbody>
    </table>
</div>

                <!-- Pagination -->
                <div class="flex justify-between items-center mt-4" id="pagination-container">
                    <div class="text-sm text-gray-600" id="pagination-info">
                        Showing {{ $units->firstItem() ?? 0 }} to {{ $units->lastItem() ?? 0 }} of {{ $units->total() }} results
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Custom Pagination - Copied from vehicles page -->
                        <nav>
                            <ul class="pagination">
                                <li class="page-item {{ $units->currentPage() <= 1 ? 'disabled' : '' }}">
                                    <a class="page-link {{ $units->currentPage() <= 1 ? 'disabled-link' : '' }}" 
                                       href="{{ $units->currentPage() <= 1 ? 'javascript:void(0)' : $units->previousPageUrl() }}"
                                       {{ $units->currentPage() <= 1 ? 'aria-disabled="true" tabindex="-1"' : '' }}>Prev</a>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">{{ $units->currentPage() }}</span>
                                </li>
                                <li class="page-item {{ $units->currentPage() >= $units->lastPage() ? 'disabled' : '' }}">
                                    <a class="page-link {{ $units->currentPage() >= $units->lastPage() ? 'disabled-link' : '' }}" 
                                       href="{{ $units->currentPage() >= $units->lastPage() ? 'javascript:void(0)' : $units->nextPageUrl() }}"
                                       {{ $units->currentPage() >= $units->lastPage() ? 'aria-disabled="true" tabindex="-1"' : '' }}>Next</a>
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
<!-- SweetAlert2 for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    width: 10px;
    height: 10px;
    margin-right: 2px;
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
                window.location.href = '{{ route("admin.units.index") }}';
                e.preventDefault();
            }
        });
    }
    
    // Handle Add Unit Modal
    const addUnitBtn = document.getElementById('addUnitBtn');
    const addUnitModalElement = document.getElementById('addUnitModal');
    const addUnitModal = new bootstrap.Modal(addUnitModalElement);
    const addUnitForm = document.getElementById('addUnitForm');
    const saveUnitBtn = document.getElementById('saveUnitBtn');
    const saveUnitBtnText = document.getElementById('saveUnitBtnText');
    const saveUnitBtnSpinner = document.getElementById('saveUnitBtnSpinner');
    const cancelUnitButton = document.getElementById('cancelUnitButton');
    
    // Track if modal was closed via cancel button
    let closedViaCancel = false;
    
    if(addUnitBtn) {
        addUnitBtn.addEventListener('click', function() {
            // Only reset form if it was closed via cancel button
            if (closedViaCancel) {
                addUnitForm.reset();
                // Clear previous errors
                clearModalErrors();
                // Reset the flag
                closedViaCancel = false;
            }
            // Show modal
            addUnitModal.show();
        });
    }
    
    // Handle backdrop click (preserve data)
    addUnitModalElement.addEventListener('hide.bs.modal', function(event) {
        // Check if it's a backdrop click (not programmatic close)
        if (event.target === addUnitModalElement) {
            // Don't reset form on backdrop click - preserve data
            closedViaCancel = false;
        }
    });
    
    // Handle Cancel button
    if(cancelUnitButton) {
        cancelUnitButton.addEventListener('click', function() {
            // Clear the form completely
            addUnitForm.reset();
            // Also clear any validation errors
            addUnitForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            addUnitForm.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            // Set flag to indicate cancel was clicked
            closedViaCancel = true;
            // Close the modal
            addUnitModal.hide();
        });
    }
    
    if(addUnitForm) {
        addUnitForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            clearModalErrors();
            
    
            // Submit form via AJAX
            const formData = new FormData(addUnitForm);
            
            // Log form data for debugging
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            fetch('{{ route("admin.units.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                console.log('Response headers:', [...response.headers.entries()]);
                
                // Check if response is successful (200-299)
                if (!response.ok) {
                    return response.text().then(text => {
                        console.log('Error response text:', text);
                        let errorMessage = 'Server error occurred';
                        try {
                            const errorData = JSON.parse(text);
                            errorMessage = errorData.message || errorMessage;
                        } catch (e) {
                            errorMessage = text || errorMessage;
                        }
                        throw new Error(errorMessage);
                    });
                }
                
                // Try to parse as JSON
                return response.text().then(text => {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('Invalid JSON response from server: ' + text);
                    }
                });
            })
            .then(data => {
                console.log('Parsed data:', data);
                
                // Check if we have a success property
                if (data && data.success === true) {
                    // First show loading alert for 2 seconds with proper loading spinner
                    Swal.fire({
                        title: 'Creating Unit...',
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
                            text: data.message || 'Unit created successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            didOpen: () => {
                                // Close modal immediately
                                addUnitModal.hide();
                            }
                        }).then(() => {
                            // Refresh page after success message
                            location.reload();
                        });
                    }, 2000);
                } else if (data && data.errors) {
                    // Handle validation errors
                    Object.keys(data.errors).forEach(field => {
                        const errorDiv = document.getElementById(field + '_error');
                        if(errorDiv) {
                            errorDiv.textContent = data.errors[field][0];
                            errorDiv.style.display = 'block';
                            // Add error class to input
                            const input = document.getElementById('modal_' + field);
                            if(input) {
                                input.classList.add('is-invalid');
                            }
                        }
                    });
                } else {
                    // Handle other error responses
                    throw new Error(data.message || 'An unexpected error occurred');
                }
            })
            .catch(error => {
                console.error('Caught error:', error);
                console.error('Error name:', error.name);
                console.error('Error message:', error.message);
                console.error('Error stack:', error.stack);
                
                let errorMessage = 'An error occurred while saving the unit';
                
                // Provide more specific error messages
                if (error.message.includes('Failed to fetch')) {
                    errorMessage = 'Network error: Unable to connect to server';
                } else if (error.message.includes('JSON')) {
                    errorMessage = 'Server response format error';
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonColor: '#1e6031'
                });
            })
            .finally(() => {
                // Reset button state
                saveUnitBtn.disabled = false;
                saveUnitBtnText.textContent = 'Create Unit';
                saveUnitBtnSpinner.style.display = 'none';
            });
        });
    }
    
    // Function to clear modal errors
    function clearModalErrors() {
        const errorDivs = document.querySelectorAll('.invalid-feedback');
        errorDivs.forEach(div => {
            div.textContent = '';
            div.style.display = 'none';
        });
        
        const inputs = document.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
    }
    
    // Handle Edit Unit Modal
    const editUnitModal = new bootstrap.Modal(document.getElementById('editUnitModal'));
    const editUnitForm = document.getElementById('editUnitForm');
    const updateUnitBtn = document.getElementById('updateUnitBtn');
    const updateUnitBtnText = document.getElementById('updateUnitBtnText');
    const updateUnitBtnSpinner = document.getElementById('updateUnitBtnSpinner');
    const cancelEditUnitButton = document.getElementById('cancelEditUnitButton');
    
    // Add event listeners to edit buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-unit-btn')) {
            e.preventDefault();
            const button = e.target.closest('.edit-unit-btn');
            const unitId = button.getAttribute('data-id');
            const unitName = button.getAttribute('data-name');
            const unitAbbr = button.getAttribute('data-abbr');
            const unitCode = button.getAttribute('data-code');
            const divisionId = button.getAttribute('data-division');
            const isActive = button.getAttribute('data-isactive');
            
            console.log('Edit button clicked - data values:');
            console.log('unitId:', unitId);
            console.log('unitName:', unitName);
            console.log('unitAbbr:', unitAbbr);
            console.log('unitCode:', unitCode);
            console.log('divisionId:', divisionId);
            console.log('isActive:', isActive);
            
            // Store original database data
            const originalData = {
                unitId: unitId,
                unitName: unitName,
                unitAbbr: unitAbbr,
                unitCode: unitCode,
                divisionId: divisionId,
                isActive: isActive
            };
            
            // Check if we should use preserved data or original data
            if (editUnitModal.closedViaCancel || !editUnitModal.preservedData || editUnitModal.currentUnitId !== unitId) {
                // Use original database data (either cancelled before or different unit)
                editUnitModal.currentUnitData = originalData;
                editUnitModal.preservedData = null;
                // Reset the flag after using it
                editUnitModal.closedViaCancel = false;
            } else {
                // Use preserved data from previous edit
                editUnitModal.currentUnitData = editUnitModal.preservedData;
            }
            
            // Store current unit ID for comparison
            editUnitModal.currentUnitId = unitId;
            
            // Clear previous errors
            clearEditModalErrors();
            
            // Show modal
            editUnitModal.show();
        }
    });
    
    // Handle modal hide to determine if data should be preserved
    document.getElementById('editUnitModal').addEventListener('hide.bs.modal', function(event) {
        // Preserve data if NOT closed via cancel button (i.e., backdrop click, escape key, or programmatic close)
        if (!editUnitModal.closedViaCancel) {
            // Save current form data for preservation
            editUnitModal.preservedData = {
                unitId: document.getElementById('edit_unit_id').value,
                unitName: document.getElementById('edit_unit_name').value,
                unitAbbr: document.getElementById('edit_unit_abbr').value,
                unitCode: document.getElementById('edit_unit_code').value,
                divisionId: document.getElementById('edit_division_id').value,
                isActive: document.getElementById('edit_unit_isactive').value
            };
        }
        // If closed via cancel button, preservedData remains null/unchanged
    });
    
    // Handle modal shown event to set values after modal is fully rendered
    document.getElementById('editUnitModal').addEventListener('shown.bs.modal', function() {
        if (editUnitModal.currentUnitData) {
            const { unitId, unitName, unitAbbr, unitCode, divisionId, isActive } = editUnitModal.currentUnitData;
                        
            console.log('Setting form values in modal:');
            console.log('unitId to set:', unitId);
            console.log('divisionId to set:', divisionId);
                        
            // Set form values after modal is shown
            const unitIdField = document.getElementById('edit_unit_id');
            if (unitIdField) {
                unitIdField.value = unitId;
                console.log('Unit ID field set to:', unitIdField.value);
            } else {
                console.error('Edit unit ID field not found!');
            }
                        
            const unitNameField = document.getElementById('edit_unit_name');
            if (unitNameField) {
                unitNameField.value = unitName;
            }
                        
            const unitAbbrField = document.getElementById('edit_unit_abbr');
            if (unitAbbrField) {
                unitAbbrField.value = unitAbbr;
            }
                        
            const unitCodeField = document.getElementById('edit_unit_code');
            if (unitCodeField) {
                unitCodeField.value = unitCode;
            }
                        
            // Ensure the division select is properly set
            const divisionSelect = document.getElementById('edit_division_id');
            if (divisionSelect) {
                // Wait a bit to ensure options are loaded, then set the value
                setTimeout(() => {
                    console.log('Available options:', Array.from(divisionSelect.options).map(opt => ({value: opt.value, text: opt.text})));
                    console.log('Attempting to set divisionId:', divisionId);
                    console.log('Division select element:', divisionSelect);
                    console.log('Number of options:', divisionSelect.options.length);
                                
                    // Try setting the value
                    divisionSelect.value = divisionId;
                                
                    // Check if the value was set successfully
                    if (divisionSelect.value != divisionId) {
                        // If not set successfully, manually select the option
                        console.log('Value not set via .value, trying manual selection');
                        let optionFound = false;
                        Array.from(divisionSelect.options).forEach(option => {
                            if (option.value == divisionId) {
                                option.selected = true;
                                optionFound = true;
                                console.log('Manually selected option with value:', option.value);
                            } else {
                                option.selected = false;
                            }
                        });
                                    
                        if (!optionFound) {
                            console.warn('Division ID not found in options:', divisionId);
                        }
                    } else {
                        console.log('Division value set successfully:', divisionSelect.value);
                    }
                                
                    console.log('Final selected value:', divisionSelect.value);
                }, 300); // Increased delay to ensure options are loaded
            } else {
                console.error('Division select field not found!');
            }
                        
            const statusSelect = document.getElementById('edit_unit_isactive');
            if (statusSelect) {
                // Ensure proper handling of 0 (inactive) and 1 (active) values
                // The isActive value might be 0, 1, '0', '1', true, false, etc.
                let finalValue;
                if (isActive == 0 || isActive === false || isActive === '0') {
                    finalValue = '0'; // Inactive
                } else {
                    finalValue = '1'; // Active
                }
                statusSelect.value = finalValue;
            }
        } else {
            console.error('No current unit data available!');
        }
    });
    
    // Handle Cancel button for edit modal
    if(cancelEditUnitButton) {
        cancelEditUnitButton.addEventListener('click', function() {
            // Clear the form completely
            editUnitForm.reset();
            // Also clear any validation errors
            editUnitForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            editUnitForm.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            // Set flag to indicate cancel was clicked
            editUnitModal.closedViaCancel = true;
            // Clear preserved data to ensure orignal data is loaded next time
            editUnitModal.preservedData = null;
            // Close the modal
            editUnitModal.hide();
        });
    }
    
    // Handle edit form submission
    if(editUnitForm) {
        editUnitForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            clearEditModalErrors();
       
            
            const unitId = document.getElementById('edit_unit_id').value;
            console.log('Editing unit ID:', unitId);
            
            if (!unitId) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Unit ID is missing. Cannot update unit.',
                    icon: 'error',
                    confirmButtonColor: '#1e6031'
                });
                return;
            }
            
            const formData = new FormData(editUnitForm);
            
            // Add the _method field to simulate PUT request
            formData.append('_method', 'PUT');
            
            // Submit form via AJAX using POST with _method field
            console.log('Submitting to URL:', `/admin/units/${unitId}`);
            fetch(`/admin/units/${unitId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                // Check if response is successful (200-299)
                if (!response.ok) {
                    return response.text().then(text => {
                        console.log('Error response text:', text);
                        let errorMessage = 'Server error occurred';
                        try {
                            const errorData = JSON.parse(text);
                            errorMessage = errorData.message || errorMessage;
                        } catch (e) {
                            errorMessage = text || errorMessage;
                        }
                        throw new Error(errorMessage);
                    });
                }
                
                // Try to parse as JSON
                return response.text().then(text => {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('Invalid JSON response from server: ' + text);
                    }
                });
            })
            .then(data => {
                console.log('Parsed data:', data);
                
                // Check if we have a success property
                if (data && data.success === true) {
                    // First show loading alert for 2 seconds with proper loading spinner
                    Swal.fire({
                        title: 'Updating Unit...',
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
                            text: data.message || 'Unit updated successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            didOpen: () => {
                                // Close modal immediately
                                editUnitModal.hide();
                            }
                        }).then(() => {
                            // Refresh page after success message
                            location.reload();
                        });
                    }, 2000);
                } else if (data && data.errors) {
                    // Handle validation errors
                    Object.keys(data.errors).forEach(field => {
                        const errorDiv = document.getElementById('edit_' + field + '_error');
                        if(errorDiv) {
                            errorDiv.textContent = data.errors[field][0];
                            errorDiv.style.display = 'block';
                            // Add error class to input
                            const input = document.getElementById('edit_' + field);
                            if(input) {
                                input.classList.add('is-invalid');
                            }
                        }
                    });
                } else {
                    // Handle other error responses
                    throw new Error(data.message || 'An unexpected error occurred');
                }
            })
            .catch(error => {
                console.error('Caught error:', error);
                console.error('Error name:', error.name);
                console.error('Error message:', error.message);
                console.error('Error stack:', error.stack);
                
                let errorMessage = 'An error occurred while updating the unit';
                
                // Provide more specific error messages
                if (error.message.includes('Failed to fetch')) {
                    errorMessage = 'Network error: Unable to connect to server';
                } else if (error.message.includes('JSON')) {
                    errorMessage = 'Server response format error';
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonColor: '#1e6031'
                });
            })
            .finally(() => {
                // Re-enable the submit button
                updateUnitBtn.disabled = false;
                updateUnitBtnText.textContent = 'Update Unit';
                updateUnitBtnSpinner.style.display = 'none';
            });
        });
    }
    
    // Function to clear edit modal errors
    function clearEditModalErrors() {
        const errorDivs = document.querySelectorAll('#editUnitModal .invalid-feedback');
        errorDivs.forEach(div => {
            div.textContent = '';
            div.style.display = 'none';
        });
        
        const inputs = document.querySelectorAll('#editUnitModal .form-control, #editUnitModal .form-select');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
    }
});
</script>

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header" style="background-color:#1e6031; color:white;">
                <h5 class="modal-title fw-bold">
                    Add New Unit
                </h5>
            </div>
            <form id="addUnitForm">
                @csrf
                <!-- Hidden Status Field (always Active) -->
                <input type="hidden" name="unit_isactive" value="1">
                
                <div class="modal-body px-3 py-2">
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Unit Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm border-success" id="modal_unit_name" name="unit_name" placeholder="Enter unit name" required>
                        <div class="invalid-feedback" id="unit_name_error"></div>
                    </div>
                    
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Abbreviation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm border-success" id="modal_unit_abbr" name="unit_abbr" placeholder="Enter abbreviation (e.g., HRM, MIS)" required>
                        <div class="invalid-feedback" id="unit_abbr_error"></div>
                    </div>
                    
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Division <span class="text-danger">*</span></label>
                        <select name="division_id" id="modal_division_id" class="form-control form-control-sm border-success" required>
                            <option value="">Select Division</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id_division }}">
                                    {{ $division->division_name }} ({{ $division->office->office_abbr }})
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="division_id_error"></div>
                    </div>
                    
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm border-success" id="modal_unit_code" name="unit_code" placeholder="Enter unit code (max 5 chars)" maxlength="5" required>
                        <div class="invalid-feedback" id="unit_code_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="cancelUnitButton">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success" id="saveUnitBtn">
                        <span id="saveUnitBtnText">Create Unit</span>
                        <span id="saveUnitBtnSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Unit Modal -->
<div class="modal fade" id="editUnitModal" tabindex="-1" aria-labelledby="editUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header" style="background-color:#1e6031; color:white;">
                <h5 class="modal-title fw-bold">
                    Edit Unit
                </h5>
            </div>
          <form id="editUnitForm" method="POST">
    @csrf
    @method('PUT')
                <div class="modal-body px-3 py-2">
             <input type="hidden" id="edit_unit_id" name="id">
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Unit Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm border-success" id="edit_unit_name" name="unit_name" placeholder="Enter unit name" required>
                        <div class="invalid-feedback" id="edit_unit_name_error"></div>
                    </div>
                    
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Abbreviation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm border-success" id="edit_unit_abbr" name="unit_abbr" placeholder="Enter abbreviation (e.g., HRM, MIS)" required>
                        <div class="invalid-feedback" id="edit_unit_abbr_error"></div>
                    </div>
                    
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Division <span class="text-danger">*</span></label>
                        <select name="division_id" id="edit_division_id" class="form-control form-control-sm border-success" required>
                            <option value="">Select Division</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id_division }}">
                                    {{ $division->division_name }} ({{ $division->office->office_abbr }})
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="edit_division_id_error"></div>
                    </div>
                    
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm border-success" id="edit_unit_code" name="unit_code" placeholder="Enter unit code (max 5 chars)" maxlength="5" required>
                        <div class="invalid-feedback" id="edit_unit_code_error"></div>
                    </div>
                    
                    <div class="mb-2">
                        <label class="small fw-semibold text-success">Status <span class="text-danger">*</span></label>
                        <select name="unit_isactive" id="edit_unit_isactive" class="form-control form-control-sm border-success" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <div class="invalid-feedback" id="edit_unit_isactive_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="cancelEditUnitButton">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success" id="updateUnitBtn">
                        <span id="updateUnitBtnText">Update Unit</span>
                        <span id="updateUnitBtnSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('click', function(e) {

    const btn = e.target.closest('.edit-unit-btn');
    if(!btn) return;

    // Get values from button
    const unitId     = btn.dataset.id;
    const unitName   = btn.dataset.name;
    const unitAbbr   = btn.dataset.abbr;
    const unitCode   = btn.dataset.code;
    const divisionId = btn.dataset.division;
    const isActive   = btn.dataset.isactive;

    console.log("Edit clicked. Unit ID:", unitId); // DEBUG

    // ===== VERY IMPORTANT: Hidden ID =====
    const hiddenId = document.getElementById('edit_unit_id');
    if(hiddenId){
        hiddenId.value = unitId;
    } else {
        console.error("Hidden input edit_unit_id NOT FOUND");
        return;
    }

    // ===== Populate fields =====
    document.getElementById('edit_unit_name').value = unitName ?? '';
    document.getElementById('edit_unit_abbr').value = unitAbbr ?? '';
    document.getElementById('edit_unit_code').value = unitCode ?? '';

    // ===== Division select =====
    const divisionSelect = document.getElementById('edit_division_id');
    if(divisionSelect){
        divisionSelect.value = divisionId;
    }

    // ===== Status select =====
    const statusSelect = document.getElementById('edit_unit_isactive');
    if(statusSelect){
        statusSelect.value = isActive;
    }

    // ===== Show modal =====
    if(editUnitModal){
        // Clear previous errors
        clearEditModalErrors();
        
        // Store original database data
        editUnitModal.currentUnitData = {
            unitId: unitId,
            unitName: unitName,
            unitAbbr: unitAbbr,
            unitCode: unitCode,
            divisionId: divisionId,
            isActive: isActive
        };
        
        // Reset the closedViaCancel flag when opening via edit button
        editUnitModal.closedViaCancel = false;
        
        // Show the modal
        editUnitModal.show();
    } else {
        console.error("Modal editUnitModal NOT FOUND");
    }

});
</script>
</x-admin-layout>