<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="background-color:#1e6031; color:white;">
                <h5 class="modal-title fw-bold" id="addEmployeeModalLabel">
                    <svg class="inline-block h-5 w-5 text-white mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Add New Employee
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Modal Body -->
            <form method="post" action="{{ route('admin.employees.store') }}" id="addEmployeeForm">
                @csrf
                
                <div class="modal-body px-4 py-3">
                    <!-- Personal Information Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-success mb-3">Personal Information</h6>
                        
                        <!-- First Row (All fields in one line) -->
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm border-success" name="first_name" placeholder="Enter first name" required>
                                <div class="invalid-feedback" id="first_name_error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm border-success" name="last_name" placeholder="Enter last name" required>
                                <div class="invalid-feedback" id="last_name_error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Middle Initial</label>
                                <input type="text" class="form-control form-control-sm border-success" name="middle_initial" placeholder="Enter middle initial" maxlength="10">
                                <div class="invalid-feedback" id="middle_initial_error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Extension Name</label>
                                <input type="text" class="form-control form-control-sm border-success" name="ext_name" placeholder="Enter extension name" maxlength="10">
                                <div class="invalid-feedback" id="ext_name_error"></div>
                            </div>
                        </div>
                        
                        <!-- Third Row (All fields in one row) -->
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Sex <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm border-success" name="sex" required>
                                    <option value="">Select Sex</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                                <div class="invalid-feedback" id="sex_error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Prefix</label>
                                <input type="text" class="form-control form-control-sm border-success" name="prefix" placeholder="Enter prefix" maxlength="10">
                                <div class="invalid-feedback" id="prefix_error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-sm border-success" name="email" placeholder="Enter email address" required>
                                <div class="invalid-feedback" id="email_error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Contact Number</label>
                                <input type="tel" class="form-control form-control-sm border-success" name="contact_num" placeholder="Enter contact number">
                                <div class="invalid-feedback" id="contact_num_error"></div>
                            </div>
                        </div>
                        
                        <!-- Fifth Row -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control form-control-sm border-success" name="password" placeholder="Enter password" required>
                                <div class="invalid-feedback" id="password_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control form-control-sm border-success" name="password_confirmation" placeholder="Confirm password" required>
                                <div class="invalid-feedback" id="password_confirmation_error"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Position Information Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-success mb-3">Primary Position Information</h6>
                        
                        <!-- First Row -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Position Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm border-success" name="position_name" placeholder="Enter position name" required>
                                <div class="invalid-feedback" id="position_name_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Class</label>
                                <select class="form-control form-control-sm border-success" name="class_id">
                                    <option value="">Select Class (Optional)</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="class_id_error"></div>
                            </div>
                        </div>
                        
                        <!-- Second Row -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Office</label>
                                <select class="form-control form-control-sm border-success" name="office_id" id="modal_office_id">
                                    <option value="">Select Office (Optional)</option>
                                    @foreach($offices as $office)
                                        <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="office_id_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Division</label>
                                <select class="form-control form-control-sm border-success" name="division_id" id="modal_division_id">
                                    <option value="">Select Division (Optional)</option>
                                </select>
                                <div class="invalid-feedback" id="division_id_error"></div>
                            </div>
                        </div>
                        
                        <!-- Third Row -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Unit</label>
                                <select class="form-control form-control-sm border-success" name="unit_id" id="modal_unit_id">
                                    <option value="">Select Unit (Optional)</option>
                                </select>
                                <div class="invalid-feedback" id="unit_id_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Subunit</label>
                                <select class="form-control form-control-sm border-success" name="subunit_id" id="modal_subunit_id">
                                    <option value="">Select Subunit (Optional)</option>
                                </select>
                                <div class="invalid-feedback" id="subunit_id_error"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Positions Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-success mb-3">Additional Positions</h6>
                        
                        <div id="modal_additionalPositionsContainer">
                            <!-- Dynamic positions will be added here -->
                        </div>
                        
                        <button type="button" id="modal_addPositionBtn" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Add Another Position
                        </button>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="modal_saveEmployeeBtn">
                        <i class="fas fa-save me-1"></i>Save Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for modal functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('addEmployeeModal');
    const form = document.getElementById('addEmployeeForm');
    const saveBtn = document.getElementById('modal_saveEmployeeBtn');
    
    // Use pre-loaded cascading data
    const cascadingData = @json($cascadingData);
    
    // Track whether the modal was closed by clicking the cancel button
    let isCancelled = false;
    
    // Store form data when modal is hidden (except when cancelled)
    let storedFormData = {};
    
    // Modal event listeners
    if (modal) {
        // Set flag when cancel button is clicked
        const cancelButton = modal.querySelector('.btn-secondary[data-bs-dismiss="modal"]');
        if (cancelButton) {
            cancelButton.addEventListener('click', function() {
                isCancelled = true;
            });
        }
        
        // Store form data when modal is hidden (unless cancelled)
        modal.addEventListener('hidden.bs.modal', function () {
            if (!isCancelled) {
                // Store current form data
                const inputs = form.querySelectorAll('input');
                const selects = form.querySelectorAll('select');
                
                inputs.forEach(input => {
                    if (input.type !== 'password') { // Don't store password fields for security
                        storedFormData[input.name] = input.value;
                    }
                });
                
                selects.forEach(select => {
                    storedFormData[select.name] = select.value;
                });
                
                // Store additional positions
                const additionalPositionsContainer = document.getElementById('modal_additionalPositionsContainer');
                if (additionalPositionsContainer) {
                    storedFormData.additionalPositionsHTML = additionalPositionsContainer.innerHTML;
                    storedFormData.positionCounter = modalPositionCounter;
                }
            } else {
                // If cancelled, reset form and clear stored data
                form.reset();
                storedFormData = {};
                // Clear any error messages
                const errorElements = form.querySelectorAll('.invalid-feedback');
                errorElements.forEach(element => {
                    element.textContent = '';
                    element.style.display = 'none';
                });
                // Reset selects
                const selects = form.querySelectorAll('select');
                selects.forEach(select => {
                    select.classList.remove('is-invalid');
                });
                // Reset inputs
                const inputs = form.querySelectorAll('input');
                inputs.forEach(input => {
                    input.classList.remove('is-invalid');
                });
                // Clear additional positions container
                const additionalPositionsContainer = document.getElementById('modal_additionalPositionsContainer');
                if (additionalPositionsContainer) {
                    additionalPositionsContainer.innerHTML = '';
                }
                // Reset counter
                modalPositionCounter = 0;
            }
            
            // Reset the cancelled flag
            isCancelled = false;
        });
        
        // Restore form data when modal is shown
        modal.addEventListener('shown.bs.modal', function () {
            // Restore stored form data
            Object.keys(storedFormData).forEach(key => {
                if (key === 'additionalPositionsHTML') {
                    const container = document.getElementById('modal_additionalPositionsContainer');
                    if (container) {
                        container.innerHTML = storedFormData[key];
                    }
                } else if (key === 'positionCounter') {
                    modalPositionCounter = storedFormData[key];
                } else {
                    const element = form.querySelector(`[name="${key}"]`);
                    if (element && element.type !== 'password') { // Don't restore password fields
                        element.value = storedFormData[key];
                    }
                }
            });
        });
    }
    
    // Function to validate all position names before form submission
    function validateAllPositionNames() {
        let isValid = true;
        
        // Check primary position name
        const primaryPositionName = document.querySelector('input[name="position_name"]');
        if (primaryPositionName && primaryPositionName.value.trim() === '') {
            const errorElement = document.getElementById('position_name_error');
            if (errorElement) {
                errorElement.textContent = 'Primary position name is required.';
                errorElement.style.display = 'block';
            }
            primaryPositionName.classList.add('is-invalid');
            isValid = false;
        } else if (primaryPositionName) {
            // Clear error if field is valid
            primaryPositionName.classList.remove('is-invalid');
            const errorElement = document.getElementById('position_name_error');
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        }
        
        // Check all additional position names
        const additionalPositionInputs = document.querySelectorAll('#modal_additionalPositionsContainer input[name*="[position_name]"]');
        additionalPositionInputs.forEach((input, index) => {
            if (input.value.trim() === '') {
                // Add error styling to empty position field
                input.classList.add('is-invalid');
                
                // Create or update error message
                const fieldName = input.name.replace('[]', '').replace('[', '_').replace(']', '');
                let errorElement = document.getElementById(fieldName + '_error');
                if (!errorElement) {
                    // Create error element if it doesn't exist
                    errorElement = document.createElement('div');
                    errorElement.id = fieldName + '_error';
                    errorElement.className = 'invalid-feedback';
                    input.parentNode.appendChild(errorElement);
                }
                errorElement.textContent = 'Position name is required.';
                errorElement.style.display = 'block';
                
                isValid = false;
            } else {
                // Clear error if field is valid
                input.classList.remove('is-invalid');
                const fieldName = input.name.replace('[]', '').replace('[', '_').replace(']', '');
                const errorElement = document.getElementById(fieldName + '_error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
        });
        
        return isValid;
    }

    // Form submission with AJAX
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // First, validate all position names
            if (!validateAllPositionNames()) {
                // If validation fails, scroll to the first invalid field
                const firstInvalidField = form.querySelector('.is-invalid');
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }
            
            // Show loading state
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Saving...';
            
            // Clear previous errors
            const errorElements = form.querySelectorAll('.invalid-feedback');
            errorElements.forEach(element => {
                element.textContent = '';
                element.style.display = 'none';
            });
            
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
            
            // Submit form via AJAX
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Employee created successfully!',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    
                    // Close modal
                    bootstrap.Modal.getInstance(modal).hide();
                    
                    // Reload page or update table
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    // Show validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorElement = document.getElementById(field + '_error');
                            const inputElement = form.querySelector(`[name="${field}"]`);
                            
                            if (errorElement) {
                                errorElement.textContent = data.errors[field][0];
                                errorElement.style.display = 'block';
                            }
                            
                            if (inputElement) {
                                inputElement.classList.add('is-invalid');
                            }
                        });
                    } else {
                        // Show general error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'An error occurred while creating the employee.',
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                });
            })
            .finally(() => {
                // Reset button
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save me-1"></i>Save Employee';
            });
        });
    }
    
    // Cascading dropdowns for modal
    const modalOfficeSelect = document.getElementById('modal_office_id');
    const modalDivisionSelect = document.getElementById('modal_division_id');
    const modalUnitSelect = document.getElementById('modal_unit_id');
    const modalSubunitSelect = document.getElementById('modal_subunit_id');
    
    if (modalOfficeSelect) {
        modalOfficeSelect.addEventListener('change', function() {
            const officeId = this.value;
            modalDivisionSelect.innerHTML = '<option value="">Select Division</option>';
            modalUnitSelect.innerHTML = '<option value="">Select Unit</option>';
            modalSubunitSelect.innerHTML = '<option value="">Select Subunit</option>';
            
            if (officeId && cascadingData.divisions[officeId]) {
                cascadingData.divisions[officeId].forEach(division => {
                    modalDivisionSelect.innerHTML += '<option value="' + division.id_division + '">' + division.division_name + '</option>';
                });
            }
        });
    }
    
    if (modalDivisionSelect) {
        modalDivisionSelect.addEventListener('change', function() {
            const divisionId = this.value;
            modalUnitSelect.innerHTML = '<option value="">Select Unit</option>';
            modalSubunitSelect.innerHTML = '<option value="">Select Subunit</option>';
            
            if (divisionId && cascadingData.units[divisionId]) {
                cascadingData.units[divisionId].forEach(unit => {
                    modalUnitSelect.innerHTML += '<option value="' + unit.id_unit + '">' + unit.unit_name + '</option>';
                });
            }
        });
    }
    
    if (modalUnitSelect) {
        modalUnitSelect.addEventListener('change', function() {
            const unitId = this.value;
            modalSubunitSelect.innerHTML = '<option value="">Select Subunit</option>';
            
            if (unitId && cascadingData.subunits[unitId]) {
                cascadingData.subunits[unitId].forEach(subunit => {
                    modalSubunitSelect.innerHTML += '<option value="' + subunit.id_subunit + '">' + subunit.subunit_name + '</option>';
                });
            }
        });
    }
    
    // Additional positions functionality
    let modalPositionCounter = 0;
    
    if (document.getElementById('modal_addPositionBtn')) {
        document.getElementById('modal_addPositionBtn').addEventListener('click', function() {
            // Check if the primary position name is filled
            const primaryPositionName = document.querySelector('input[name="position_name"]');
            if (primaryPositionName && primaryPositionName.value.trim() === '') {
                // Show error message for primary position
                const errorElement = document.getElementById('position_name_error');
                if (errorElement) {
                    errorElement.textContent = 'Primary position name is required before adding additional positions.';
                    errorElement.style.display = 'block';
                }
                primaryPositionName.classList.add('is-invalid');
                
                // Scroll to the primary position field
                primaryPositionName.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return; // Don't add another position if primary is empty
            }
            
            // Check if any existing additional position has an empty name
            const additionalPositionInputs = document.querySelectorAll('#modal_additionalPositionsContainer input[name*="[position_name]"]');
            let hasEmptyPosition = false;
            let emptyPositionInput = null;
            
            additionalPositionInputs.forEach(input => {
                if (input.value.trim() === '') {
                    hasEmptyPosition = true;
                    emptyPositionInput = input;
                    // Add error styling to empty position field
                    input.classList.add('is-invalid');
                    // Create or update error message
                    const fieldName = input.name.replace('[]', '').replace('[', '_').replace(']', '');
                    let errorElement = document.getElementById(fieldName + '_error');
                    if (!errorElement) {
                        errorElement = document.createElement('div');
                        errorElement.id = fieldName + '_error';
                        errorElement.className = 'invalid-feedback';
                        input.parentNode.appendChild(errorElement);
                    }
                    errorElement.textContent = 'Position name is required.';
                    errorElement.style.display = 'block';
                } else {
                    // Clear error if field is not empty
                    input.classList.remove('is-invalid');
                    const fieldName = input.name.replace('[]', '').replace('[', '_').replace(']', '');
                    const errorElement = document.getElementById(fieldName + '_error');
                    if (errorElement) {
                        errorElement.style.display = 'none';
                    }
                }
            });
            
            // If there are empty positions, don't add a new one
            if (hasEmptyPosition && emptyPositionInput) {
                emptyPositionInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            
            modalPositionCounter++;
            const container = document.getElementById('modal_additionalPositionsContainer');
            
            const positionDiv = document.createElement('div');
            positionDiv.className = 'border p-3 rounded mb-3 position-relative';
            positionDiv.dataset.positionIndex = modalPositionCounter;
            
            positionDiv.innerHTML = `
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2" aria-label="Remove"></button>
                
                <!-- First Row -->
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="small fw-semibold text-success">Position Name <span class="text-danger">*</span></label>
                        <input type="text" name="additional_positions[${modalPositionCounter}][position_name]" class="form-control form-control-sm border-success additional-position-name" placeholder="Enter position name" required>
                        <div class="invalid-feedback additional-position-error"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-semibold text-success">Class</label>
                        <select name="additional_positions[${modalPositionCounter}][class_id]" class="form-control form-control-sm border-success">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ addslashes($class->class_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Second Row -->
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="small fw-semibold text-success">Office</label>
                        <select name="additional_positions[${modalPositionCounter}][office_id]" class="form-control form-control-sm border-success modal-additional-office">
                            <option value="">Select Office</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}">{{ addslashes($office->office_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-semibold text-success">Division</label>
                        <select name="additional_positions[${modalPositionCounter}][division_id]" class="form-control form-control-sm border-success modal-additional-division">
                            <option value="">Select Division</option>
                        </select>
                    </div>
                </div>
                
                <!-- Third Row -->
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="small fw-semibold text-success">Unit</label>
                        <select name="additional_positions[${modalPositionCounter}][unit_id]" class="form-control form-control-sm border-success modal-additional-unit">
                            <option value="">Select Unit</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-semibold text-success">Subunit</label>
                        <select name="additional_positions[${modalPositionCounter}][subunit_id]" class="form-control form-control-sm border-success modal-additional-subunit">
                            <option value="">Select Subunit</option>
                        </select>
                    </div>
                </div>
            `;
            
            container.appendChild(positionDiv);
            
            // Add event listeners to the new dropdowns
            attachModalDropdownEventListeners(positionDiv, cascadingData);
            
            // Add event listener to the remove button
            positionDiv.querySelector('.btn-close').addEventListener('click', function() {
                positionDiv.remove();
                // Remove error styling if any
                const positionInput = positionDiv.querySelector('.additional-position-name');
                if (positionInput) {
                    positionInput.classList.remove('is-invalid');
                    const errorElement = positionDiv.querySelector('.additional-position-error');
                    if (errorElement) {
                        errorElement.style.display = 'none';
                    }
                }
            });
            
            // Add input event listener to validate the new position name field in real-time
            const newPositionInput = positionDiv.querySelector('.additional-position-name');
            if (newPositionInput) {
                newPositionInput.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.remove('is-invalid');
                        const errorElement = positionDiv.querySelector('.additional-position-error');
                        if (errorElement) {
                            errorElement.style.display = 'none';
                        }
                    }
                });
                
                // Add blur event listener to validate when field loses focus
                newPositionInput.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.classList.add('is-invalid');
                        const errorElement = positionDiv.querySelector('.additional-position-error');
                        if (errorElement) {
                            errorElement.textContent = 'Position name is required.';
                            errorElement.style.display = 'block';
                        }
                    }
                });
            }
        });
    }
    
    // Function to attach dropdown event listeners to a position group
    function attachModalDropdownEventListeners(group, data) {
        const officeSelect = group.querySelector('.modal-additional-office');
        const divisionSelect = group.querySelector('.modal-additional-division');
        const unitSelect = group.querySelector('.modal-additional-unit');
        const subunitSelect = group.querySelector('.modal-additional-subunit');
        
        if (officeSelect) {
            officeSelect.addEventListener('change', function() {
                const officeId = this.value;
                divisionSelect.innerHTML = '<option value="">Select Division</option>';
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                
                if (officeId && data.divisions[officeId]) {
                    data.divisions[officeId].forEach(division => {
                        divisionSelect.innerHTML += '<option value="' + division.id_division + '">' + division.division_name + '</option>';
                    });
                }
            });
        }
        
        if (divisionSelect) {
            divisionSelect.addEventListener('change', function() {
                const divisionId = this.value;
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                
                if (divisionId && data.units[divisionId]) {
                    data.units[divisionId].forEach(unit => {
                        unitSelect.innerHTML += '<option value="' + unit.id_unit + '">' + unit.unit_name + '</option>';
                    });
                }
            });
        }
        
        if (unitSelect) {
            unitSelect.addEventListener('change', function() {
                const unitId = this.value;
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                
                if (unitId && data.subunits[unitId]) {
                    data.subunits[unitId].forEach(subunit => {
                        subunitSelect.innerHTML += '<option value="' + subunit.id_subunit + '">' + subunit.subunit_name + '</option>';
                    });
                }
            });
        }
    }
    
    // Function to validate email format
    function validateEmailFormat() {
        const emailInput = document.querySelector('input[name="email"]');
        if (emailInput && emailInput.value) {
            const email = emailInput.value.trim();
            // Regular expression for email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailPattern.test(email)) {
                const errorElement = document.getElementById('email_error');
                if (errorElement) {
                    errorElement.textContent = 'Please enter a valid email address.';
                    errorElement.style.display = 'block';
                }
                emailInput.classList.add('is-invalid');
                return false;
            } else {
                emailInput.classList.remove('is-invalid');
                const errorElement = document.getElementById('email_error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
                return true;
            }
        }
        return true; // Allow empty email (though it's required by backend validation)
    }
    
    // Function to validate contact number format (numbers only, max 11 digits)
    function validateContactNumber() {
        const contactNumInput = document.querySelector('input[name="contact_num"]');
        if (contactNumInput && contactNumInput.value) {
            const contactNumber = contactNumInput.value.trim();
            // Regular expression to allow only numbers and common separators like spaces, dashes, parentheses
            const contactNumberPattern = /^[\d\s\-\+\(\)]+$/;
            
            // Extract only digits from the contact number
            const digitsOnly = contactNumber.replace(/\D/g, '');
            
            if (!contactNumberPattern.test(contactNumber)) {
                const errorElement = document.getElementById('contact_num_error');
                if (errorElement) {
                    errorElement.textContent = 'Contact number can only contain numbers and common separators (+, -, space, parentheses).';
                    errorElement.style.display = 'block';
                }
                contactNumInput.classList.add('is-invalid');
                return false;
            } else if (digitsOnly.length > 11) {
                const errorElement = document.getElementById('contact_num_error');
                if (errorElement) {
                    errorElement.textContent = 'Contact number must not exceed 11 digits.';
                    errorElement.style.display = 'block';
                }
                contactNumInput.classList.add('is-invalid');
                return false;
            } else {
                contactNumInput.classList.remove('is-invalid');
                const errorElement = document.getElementById('contact_num_error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
                return true;
            }
        }
        return true; // Allow empty contact number
    }
    
    // Function to validate password confirmation
    function validatePasswordConfirmation() {
        const passwordInput = document.querySelector('input[name="password"]');
        const confirmPasswordInput = document.querySelector('input[name="password_confirmation"]');
        
        if (passwordInput && confirmPasswordInput) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                const errorElement = document.getElementById('password_confirmation_error');
                if (errorElement) {
                    errorElement.textContent = 'Password confirmation does not match.';
                    errorElement.style.display = 'block';
                }
                confirmPasswordInput.classList.add('is-invalid');
                return false;
            } else {
                confirmPasswordInput.classList.remove('is-invalid');
                const errorElement = document.getElementById('password_confirmation_error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
                return true;
            }
        }
        return true;
    }
    
    // Add real-time validation for email input
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            validateEmailFormat();
        });
        
        emailInput.addEventListener('blur', function() {
            validateEmailFormat();
        });
    }
    
    // Add real-time validation for contact number input
    const contactNumInput = document.querySelector('input[name="contact_num"]');
    if (contactNumInput) {
        contactNumInput.addEventListener('input', function() {
            let contactNumber = this.value;
            // Allow only numbers and common separators
            let cleanedValue = contactNumber.replace(/[^0-9\s\-\+\(\)]/g, '');
            
            // Extract only digits to check count
            const digitsOnly = cleanedValue.replace(/\D/g, '');
            
            // Limit to 11 digits maximum
            if (digitsOnly.length > 11) {
                // Truncate the digits to 11 and reconstruct the value
                const first11Digits = digitsOnly.substring(0, 11);
                // We need to reconstruct the cleanedValue keeping the separators but limiting to 11 digits
                let digitCount = 0;
                cleanedValue = '';
                for (let char of contactNumber) {
                    if (/\d/.test(char)) {
                        if (digitCount < 11) {
                            cleanedValue += char;
                            digitCount++;
                        }
                    } else if (/[^\d]/.test(char) && /[\s\-\+\(\)]/.test(char)) { // Only allow separators
                        cleanedValue += char;
                    }
                }
            }
            
            if (contactNumber !== cleanedValue) {
                this.value = cleanedValue;
            }
            
            // Validate as we type
            const contactNumberPattern = /^[\d\s\-\+\(\)]+$/;
            contactNumber = this.value; // Get the possibly updated value after truncation
            const digitsInCurrent = contactNumber.replace(/\D/g, '');
            
            if (contactNumber && !contactNumberPattern.test(contactNumber)) {
                this.classList.add('is-invalid');
                const errorElement = document.getElementById('contact_num_error');
                if (errorElement) {
                    errorElement.textContent = 'Contact number can only contain numbers and common separators.';
                    errorElement.style.display = 'block';
                }
            } else if (digitsInCurrent.length > 11) {
                this.classList.add('is-invalid');
                const errorElement = document.getElementById('contact_num_error');
                if (errorElement) {
                    errorElement.textContent = 'Contact number must not exceed 11 digits.';
                    errorElement.style.display = 'block';
                }
            } else {
                this.classList.remove('is-invalid');
                const errorElement = document.getElementById('contact_num_error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
        });
    }
    
    // Add real-time validation for password confirmation
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector('input[name="password_confirmation"]');
    
    if (passwordInput && confirmPasswordInput) {
        // Validate password confirmation when typing in either field
        passwordInput.addEventListener('input', function() {
            validatePasswordConfirmation();
        });
        
        confirmPasswordInput.addEventListener('input', function() {
            validatePasswordConfirmation();
        });
        
        // Validate when focus leaves the confirm password field
        confirmPasswordInput.addEventListener('blur', function() {
            validatePasswordConfirmation();
        });
    }
    
    // Update the form submission to include these validations
    const originalFormSubmit = form.onsubmit;
    form.onsubmit = function(e) {
        e.preventDefault();
        
        // First, validate all position names
        if (!validateAllPositionNames()) {
            // If validation fails, scroll to the first invalid field
            const firstInvalidField = form.querySelector('.is-invalid');
            if (firstInvalidField) {
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }
        
        // Validate email format
        if (!validateEmailFormat()) {
            const emailInput = form.querySelector('input[name="email"]');
            if (emailInput) {
                emailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }
        
        // Validate contact number
        if (!validateContactNumber()) {
            const contactNumInput = form.querySelector('input[name="contact_num"]');
            if (contactNumInput) {
                contactNumInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }
        
        // Validate password confirmation
        if (!validatePasswordConfirmation()) {
            const confirmPasswordInput = form.querySelector('input[name="password_confirmation"]');
            if (confirmPasswordInput) {
                confirmPasswordInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }
        
        // Show loading state
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Saving...';
        
        // Clear previous errors
        const errorElements = form.querySelectorAll('.invalid-feedback');
        errorElements.forEach(element => {
            element.textContent = '';
            element.style.display = 'none';
        });
        
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
        
        // Submit form via AJAX
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show loading indicator for 2 seconds before success message
                Swal.fire({
                    title: 'Saving...',
                    html: 'Please wait while we save the employee data.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Wait for 2 seconds before showing success
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Employee created successfully!',
                        timer: 3000,
                        timerProgressBar: true
                    }).then(() => {
                        // Close modal after success message
                        bootstrap.Modal.getInstance(modal).hide();
                        
                        // Reload page or update table
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    });
                }, 2000);
            } else {
                // If loading/sweetalert is active, close it before showing error
                if (Swal.isVisible()) {
                    Swal.close();
                }
                
                // Show validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(field + '_error');
                        const inputElement = form.querySelector(`[name="${field}"]`);
                        
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.style.display = 'block';
                        }
                        
                        if (inputElement) {
                            inputElement.classList.add('is-invalid');
                        }
                    });
                    
                    // Additional client-side validation for contact number and password confirmation
                    if (data.errors.contact_num) {
                        const contactNumInput = form.querySelector('input[name="contact_num"]');
                        if (contactNumInput) {
                            contactNumInput.classList.add('is-invalid');
                        }
                    }
                    
                    if (data.errors.password) {
                        const confirmPasswordInput = form.querySelector('input[name="password_confirmation"]');
                        if (confirmPasswordInput) {
                            confirmPasswordInput.classList.add('is-invalid');
                        }
                    }
                } else {
                    // Show general error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'An error occurred while creating the employee.',
                    });
                }
            }
        })
        .catch(error => {
            // If loading/sweetalert is active, close it before showing error
            if (Swal.isVisible()) {
                Swal.close();
            }
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An unexpected error occurred.',
            });
        })
        .finally(() => {
            // Reset button
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save me-1"></i>Save Employee';
        });
    };
});
</script>