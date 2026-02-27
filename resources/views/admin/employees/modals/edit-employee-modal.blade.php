<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="background-color:#1e6031; color:white;">
                <h5 class="modal-title fw-bold" id="editEmployeeModalLabel">
                  
                    Edit Employee
                </h5>
            </div>
            
            <!-- Modal Body -->
            <form method="post" action="" id="editEmployeeForm">
                @csrf
                @method('PUT')
                
                <div class="modal-body px-4 py-3">
                    <!-- Loading indicator -->
                    <div id="editModalLoading" class="text-center py-4">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading employee data...</p>
                    </div>
                    
                    <!-- Personal Information Section -->
                    <div id="editPersonalInfoSection" style="display: none;">
                        <h6 class="fw-bold text-success mb-3">Personal Information</h6>
                        
                        <!-- First Row (All fields in one line) -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm border-success" name="first_name" id="edit_first_name" placeholder="Enter first name" required>
                                <div class="invalid-feedback" id="edit_first_name_error"></div>
                            </div>
                    
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Middle Name</label>
                                <input type="text" class="form-control form-control-sm border-success" name="middle_name" id="edit_middle_name" placeholder="Enter middle name" maxlength="255">
                                <div class="invalid-feedback" id="edit_middle_name_error"></div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm border-success" name="last_name" id="edit_last_name" placeholder="Enter last name" required>
                                <div class="invalid-feedback" id="edit_last_name_error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Extension Name</label>
                                <input type="text" class="form-control form-control-sm border-success" name="ext_name" id="edit_ext_name" placeholder="Enter extension name" maxlength="10">
                                <div class="invalid-feedback" id="edit_ext_name_error"></div>
                            </div>
                        </div>
                        
                        <!-- Third Row (All fields in one row) -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Sex <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm border-success" name="sex" id="edit_sex" required>
                                    <option value="">Select Sex</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                                <div class="invalid-feedback" id="edit_sex_error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Prefix</label>
                                <input type="text" class="form-control form-control-sm border-success" name="prefix" id="edit_prefix" placeholder="Enter prefix" maxlength="10">
                                <div class="invalid-feedback" id="edit_prefix_error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-sm border-success" name="email" id="edit_email" placeholder="Enter email address" required>
                                <div class="invalid-feedback" id="edit_email_error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-semibold text-success">Contact Number</label>
                                <input type="tel" class="form-control form-control-sm border-success" name="contact_num" id="edit_contact_num" placeholder="Enter contact number">
                                <div class="invalid-feedback" id="edit_contact_num_error"></div>
                            </div>
                        </div>
                        
                        <!-- Fifth Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Password</label>
                                <input type="password" class="form-control form-control-sm border-success" name="password" id="edit_password" placeholder="Enter new password (leave blank to keep current)">
                                <div class="invalid-feedback" id="edit_password_error"></div>
                                <small class="form-text text-muted">Leave blank to keep current password</small>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Confirm Password</label>
                                <input type="password" class="form-control form-control-sm border-success" name="password_confirmation" id="edit_password_confirmation" placeholder="Confirm new password">
                                <div class="invalid-feedback" id="edit_password_confirmation_error"></div>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Status <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm border-success" name="emp_status" id="edit_emp_status" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div class="invalid-feedback" id="edit_emp_status_error"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Position Information Section -->
                    <div id="editPositionInfoSection" style="display: none;">
                        <h6 class="fw-bold text-success mb-3">Primary Position Information</h6>
                        
                        <!-- First Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Position Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm border-success" name="position_name" id="edit_position_name" placeholder="Enter position name" required>
                                <div class="invalid-feedback" id="edit_position_name_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Class</label>
                                <select class="form-control form-control-sm border-success" name="class_id" id="edit_class_id">
                                    <option value="">Select Class (Optional)</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id_class }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="edit_class_id_error"></div>
                            </div>
                        </div>
                        
                        <!-- Second Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Office</label>
                                <select class="form-control form-control-sm border-success" name="office_id" id="edit_office_id">
                                    <option value="">Select Office (Optional)</option>
                                    @foreach($offices as $office)
                                        <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="edit_office_id_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Division</label>
                                <select class="form-control form-control-sm border-success" name="division_id" id="edit_division_id">
                                    <option value="">Select Division (Optional)</option>
                                </select>
                                <div class="invalid-feedback" id="edit_division_id_error"></div>
                            </div>
                        </div>
                        
                        <!-- Third Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Unit</label>
                                <select class="form-control form-control-sm border-success" name="unit_id" id="edit_unit_id">
                                    <option value="">Select Unit (Optional)</option>
                                </select>
                                <div class="invalid-feedback" id="edit_unit_id_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-semibold text-success">Subunit</label>
                                <select class="form-control form-control-sm border-success" name="subunit_id" id="edit_subunit_id">
                                    <option value="">Select Subunit (Optional)</option>
                                </select>
                                <div class="invalid-feedback" id="edit_subunit_id_error"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Positions Section -->
                    <div id="editAdditionalPositionsSection" style="display: none;">
                        <h6 class="fw-bold text-success mb-3">Additional Positions</h6>
                        
                        <div id="edit_additionalPositionsContainer">
                            <!-- Additional positions will be loaded here dynamically -->
                        </div>
                        
                        <button type="button" id="edit_addPositionBtn" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Add Another Position
                        </button>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="edit_saveEmployeeBtn">
                        <i class="fas fa-save me-1"></i>Update Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for edit modal functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('editEmployeeModal');
    const form = document.getElementById('editEmployeeForm');
    const saveBtn = document.getElementById('edit_saveEmployeeBtn');
    const loadingIndicator = document.getElementById('editModalLoading');
    const personalInfoSection = document.getElementById('editPersonalInfoSection');
    const positionInfoSection = document.getElementById('editPositionInfoSection');
    const additionalPositionsSection = document.getElementById('editAdditionalPositionsSection');
    
    // Use global cascading data
    const cascadingData = window.cascadingData || @json($cascadingData);
    
    // Track whether the modal was closed by clicking the cancel button
    let isCancelled = false;
    
    // Store current employee ID being edited
    let currentEmployeeId = null;
    
    // Modal event listeners
    if (modal) {
        // Set flag when cancel button is clicked
        const cancelButton = modal.querySelector('.btn-secondary[data-bs-dismiss="modal"]');
        if (cancelButton) {
            cancelButton.addEventListener('click', function() {
                isCancelled = true;
            });
        }
        
        // Reset form when modal is hidden (except when cancelled)
        modal.addEventListener('hidden.bs.modal', function () {
            if (!isCancelled) {
                // Reset form
                form.reset();
                // Clear any validation errors
                const errorElements = form.querySelectorAll('.invalid-feedback');
                errorElements.forEach(element => {
                    element.textContent = '';
                    element.style.display = 'none';
                });
                // Reset inputs
                const inputs = form.querySelectorAll('input');
                inputs.forEach(input => {
                    input.classList.remove('is-invalid');
                });
                // Clear additional positions container
                const additionalPositionsContainer = document.getElementById('edit_additionalPositionsContainer');
                if (additionalPositionsContainer) {
                    additionalPositionsContainer.innerHTML = '';
                }
                // Reset counter
                editPositionCounter = 0;
                // Hide sections and show loading
                personalInfoSection.style.display = 'none';
                positionInfoSection.style.display = 'none';
                additionalPositionsSection.style.display = 'none';
                loadingIndicator.style.display = 'block';
                // Clear current employee ID
                currentEmployeeId = null;
            }
            
            // Reset the cancelled flag
            isCancelled = false;
        });
    }
    
    // Cascading dropdowns for main form
    const officeSelect = document.getElementById('edit_office_id');
    const divisionSelect = document.getElementById('edit_division_id');
    const unitSelect = document.getElementById('edit_unit_id');
    const subunitSelect = document.getElementById('edit_subunit_id');
    
    if (officeSelect) {
        officeSelect.addEventListener('change', function() {
            const officeId = this.value;
            divisionSelect.innerHTML = '<option value="">Select Division</option>';
            unitSelect.innerHTML = '<option value="">Select Unit</option>';
            subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
            
            if (officeId && cascadingData.divisions[officeId]) {
                cascadingData.divisions[officeId].forEach(division => {
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
            
            if (divisionId && cascadingData.units[divisionId]) {
                cascadingData.units[divisionId].forEach(unit => {
                    unitSelect.innerHTML += '<option value="' + unit.id + '">' + unit.unit_name + '</option>';
                });
            }
        });
    }
    
    if (unitSelect) {
        unitSelect.addEventListener('change', function() {
            const unitId = this.value;
            subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
            
            if (unitId && cascadingData.subunits[unitId]) {
                cascadingData.subunits[unitId].forEach(subunit => {
                    subunitSelect.innerHTML += '<option value="' + subunit.id_subunit + '">' + subunit.subunit_name + '</option>';
                });
            }
        });
    }
    
    // Additional positions functionality
    let editPositionCounter = 0;
    
    if (document.getElementById('edit_addPositionBtn')) {
        document.getElementById('edit_addPositionBtn').addEventListener('click', function() {
            // Check if the primary position name is filled
            const primaryPositionName = document.getElementById('edit_position_name');
            if (primaryPositionName && primaryPositionName.value.trim() === '') {
                // Show error message for primary position
                const errorElement = document.getElementById('edit_position_name_error');
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
            const additionalPositionInputs = document.querySelectorAll('#edit_additionalPositionsContainer input[name*="[position_name]"]');
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
                    let errorElement = document.getElementById('edit_' + fieldName + '_error');
                    if (!errorElement) {
                        errorElement = document.createElement('div');
                        errorElement.id = 'edit_' + fieldName + '_error';
                        errorElement.className = 'invalid-feedback';
                        input.parentNode.appendChild(errorElement);
                    }
                    errorElement.textContent = 'Position name is required.';
                    errorElement.style.display = 'block';
                } else {
                    // Clear error if field is not empty
                    input.classList.remove('is-invalid');
                    const fieldName = input.name.replace('[]', '').replace('[', '_').replace(']', '');
                    const errorElement = document.getElementById('edit_' + fieldName + '_error');
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
            
            editPositionCounter++;
            const container = document.getElementById('edit_additionalPositionsContainer');
            
            const positionDiv = document.createElement('div');
            positionDiv.className = 'border p-3 rounded mb-3 position-relative';
            positionDiv.dataset.positionIndex = editPositionCounter;
            
            positionDiv.innerHTML = `
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-position-btn" aria-label="Remove"></button>
                
                <!-- First Row -->
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="small fw-semibold text-success">Position Name <span class="text-danger">*</span></label>
                        <input type="text" name="additional_positions[${editPositionCounter}][position_name]" class="form-control form-control-sm border-success additional-position-name" placeholder="Enter position name" required>
                        <div class="invalid-feedback additional-position-error"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-semibold text-success">Class</label>
                        <select name="additional_positions[${editPositionCounter}][class_id]" class="form-control form-control-sm border-success">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id_class }}">{{ addslashes($class->class_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Second Row -->
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="small fw-semibold text-success">Office</label>
                        <select name="additional_positions[${editPositionCounter}][office_id]" class="form-control form-control-sm border-success edit-additional-office">
                            <option value="">Select Office</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}">{{ addslashes($office->office_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-semibold text-success">Division</label>
                        <select name="additional_positions[${editPositionCounter}][division_id]" class="form-control form-control-sm border-success edit-additional-division">
                            <option value="">Select Division</option>
                        </select>
                    </div>
                </div>
                
                <!-- Third Row -->
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="small fw-semibold text-success">Unit</label>
                        <select name="additional_positions[${editPositionCounter}][unit_id]" class="form-control form-control-sm border-success edit-additional-unit">
                            <option value="">Select Unit</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-semibold text-success">Subunit</label>
                        <select name="additional_positions[${editPositionCounter}][subunit_id]" class="form-control form-control-sm border-success edit-additional-subunit">
                            <option value="">Select Subunit</option>
                        </select>
                    </div>
                </div>
            `;
            
            container.appendChild(positionDiv);
            
            // Add event listeners to the new dropdowns
            attachEditDropdownEventListeners(positionDiv, cascadingData);
            
            // Add event listener to the remove button
            positionDiv.querySelector('.remove-position-btn').addEventListener('click', function() {
                positionDiv.remove();
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
    
    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-position-btn').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.border.p-3.rounded.mb-3').remove();
        });
    });
    
    // Function to attach dropdown event listeners to a position group
    function attachEditDropdownEventListeners(group, data) {
        const officeSelect = group.querySelector('.edit-additional-office');
        const divisionSelect = group.querySelector('.edit-additional-division');
        const unitSelect = group.querySelector('.edit-additional-unit');
        const subunitSelect = group.querySelector('.edit-additional-subunit');
        
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
                        unitSelect.innerHTML += '<option value="' + unit.id + '">' + unit.unit_name + '</option>';
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
        const emailInput = document.getElementById('edit_email');
        if (emailInput && emailInput.value) {
            const email = emailInput.value.trim();
            // Regular expression for email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailPattern.test(email)) {
                const errorElement = document.getElementById('edit_email_error');
                if (errorElement) {
                    errorElement.textContent = 'Please enter a valid email address.';
                    errorElement.style.display = 'block';
                }
                emailInput.classList.add('is-invalid');
                return false;
            } else {
                emailInput.classList.remove('is-invalid');
                const errorElement = document.getElementById('edit_email_error');
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
        const contactNumInput = document.getElementById('edit_contact_num');
        if (contactNumInput && contactNumInput.value) {
            const contactNumber = contactNumInput.value.trim();
            // Regular expression to allow only numbers and common separators like spaces, dashes, parentheses
            const contactNumberPattern = /^[\d\s\-\+\(\)]+$/;
            
            // Extract only digits from the contact number
            const digitsOnly = contactNumber.replace(/\D/g, '');
            
            if (!contactNumberPattern.test(contactNumber)) {
                const errorElement = document.getElementById('edit_contact_num_error');
                if (errorElement) {
                    errorElement.textContent = 'Contact number can only contain numbers and common separators (+, -, space, parentheses).';
                    errorElement.style.display = 'block';
                }
                contactNumInput.classList.add('is-invalid');
                return false;
            } else if (digitsOnly.length > 11) {
                const errorElement = document.getElementById('edit_contact_num_error');
                if (errorElement) {
                    errorElement.textContent = 'Contact number must not exceed 11 digits.';
                    errorElement.style.display = 'block';
                }
                contactNumInput.classList.add('is-invalid');
                return false;
            } else {
                contactNumInput.classList.remove('is-invalid');
                const errorElement = document.getElementById('edit_contact_num_error');
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
        const passwordInput = document.getElementById('edit_password');
        const confirmPasswordInput = document.getElementById('edit_password_confirmation');
        
        if (passwordInput && confirmPasswordInput && passwordInput.value !== '') {
            if (passwordInput.value !== confirmPasswordInput.value) {
                const errorElement = document.getElementById('edit_password_confirmation_error');
                if (errorElement) {
                    errorElement.textContent = 'Password confirmation does not match.';
                    errorElement.style.display = 'block';
                }
                confirmPasswordInput.classList.add('is-invalid');
                return false;
            } else {
                confirmPasswordInput.classList.remove('is-invalid');
                const errorElement = document.getElementById('edit_password_confirmation_error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
                return true;
            }
        }
        return true;
    }
    
    // Add real-time validation for email input
    const emailInput = document.getElementById('edit_email');
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            validateEmailFormat();
        });
        
        emailInput.addEventListener('blur', function() {
            validateEmailFormat();
        });
    }
    
    // Add real-time validation for contact number input
    const contactNumInput = document.getElementById('edit_contact_num');
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
                const errorElement = document.getElementById('edit_contact_num_error');
                if (errorElement) {
                    errorElement.textContent = 'Contact number can only contain numbers and common separators.';
                    errorElement.style.display = 'block';
                }
            } else if (digitsInCurrent.length > 11) {
                this.classList.add('is-invalid');
                const errorElement = document.getElementById('edit_contact_num_error');
                if (errorElement) {
                    errorElement.textContent = 'Contact number must not exceed 11 digits.';
                    errorElement.style.display = 'block';
                }
            } else {
                this.classList.remove('is-invalid');
                const errorElement = document.getElementById('edit_contact_num_error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
        });
    }
    
    // Add real-time validation for password confirmation
    const passwordInput = document.getElementById('edit_password');
    const confirmPasswordInput = document.getElementById('edit_password_confirmation');
    
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
            
            // Validate email format
            if (!validateEmailFormat()) {
                const emailInput = document.getElementById('edit_email');
                if (emailInput) {
                    emailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }
            
            // Validate contact number
            if (!validateContactNumber()) {
                const contactNumInput = document.getElementById('edit_contact_num');
                if (contactNumInput) {
                    contactNumInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }
            
            // Validate password confirmation
            if (!validatePasswordConfirmation()) {
                const confirmPasswordInput = document.getElementById('edit_password_confirmation');
                if (confirmPasswordInput) {
                    confirmPasswordInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }
            
            // Show loading state
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Updating...';
            
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
                    'X-HTTP-Method-Override': 'PUT',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show 2-second loading with original text
                    Swal.fire({
                        title: 'Updating Employee...',
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
                        }).then(() => {
                            // Close the modal
                            bootstrap.Modal.getInstance(modal).hide();
                            // Reload the page to show updated data
                            location.reload();
                        });
                    });
                } else {
                    // If loading/sweetalert is active, close it before showing error
                    if (Swal.isVisible()) {
                        Swal.close();
                    }
                    
                    // Show validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorElement = document.getElementById('edit_' + field + '_error');
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
                            text: data.message || 'An error occurred while updating the employee.',
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
                saveBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Employee';
            });
        });
    }
    
    // Function to validate all position names before form submission
    function validateAllPositionNames() {
        let isValid = true;
        
        // Check primary position name
        const primaryPositionName = document.getElementById('edit_position_name');
        if (primaryPositionName && primaryPositionName.value.trim() === '') {
            const errorElement = document.getElementById('edit_position_name_error');
            if (errorElement) {
                errorElement.textContent = 'Primary position name is required.';
                errorElement.style.display = 'block';
            }
            primaryPositionName.classList.add('is-invalid');
            isValid = false;
        } else if (primaryPositionName) {
            // Clear error if field is valid
            primaryPositionName.classList.remove('is-invalid');
            const errorElement = document.getElementById('edit_position_name_error');
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        }
        
        // Check all additional position names
        const additionalPositionInputs = document.querySelectorAll('#edit_additionalPositionsContainer input[name*="[position_name]"]');
        additionalPositionInputs.forEach((input, index) => {
            if (input.value.trim() === '') {
                // Add error styling to empty position field
                input.classList.add('is-invalid');
                
                // Create or update error message
                const fieldName = input.name.replace('[]', '').replace('[', '_').replace(']', '');
                let errorElement = document.getElementById('edit_' + fieldName + '_error');
                if (!errorElement) {
                    errorElement = document.createElement('div');
                    errorElement.id = 'edit_' + fieldName + '_error';
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
                const errorElement = document.getElementById('edit_' + fieldName + '_error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
        });
        
        return isValid;
    }
});
</script>