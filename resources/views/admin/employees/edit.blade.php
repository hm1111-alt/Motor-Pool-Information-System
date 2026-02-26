<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-center items-center">
            <div class="flex justify-between items-center" style="width: 100%; max-width: 1200px;">
                <h2 class="font-bold flex items-center" style="color: #1e6031; font-size: 1.5rem; height: 32px; margin-top: 10px;">
                    <svg class="mr-2" style="width: 24px; height: 24px; color: #1e6031;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Edit Employee
                </h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center px-3 py-1 bg-gray-500 border border-gray-500 rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" style="height: 32px;">
                        <svg class="mr-1" style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="post" action="{{ route('admin.employees.update', $employee) }}" id="editEmployeeForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Personal Information Section -->
                        <div class="mb-6">
                            <h6 class="fw-bold text-success mb-3">Personal Information</h6>
                            
                            <!-- First Row (All fields in one line) -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="small fw-semibold text-success">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm border-success" name="first_name" value="{{ old('first_name', $employee->first_name) }}" placeholder="Enter first name" required>
                                    <div class="invalid-feedback" id="first_name_error"></div>
                                </div>
                        
                                <div class="col-md-3">
                                    <label class="small fw-semibold text-success">Middle Name</label>
                                    <input type="text" class="form-control form-control-sm border-success" name="middle_name" value="{{ old('middle_name', $employee->middle_name) }}" placeholder="Enter middle name" maxlength="255">
                                    <div class="invalid-feedback" id="middle_name_error"></div>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="small fw-semibold text-success">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm border-success" name="last_name" value="{{ old('last_name', $employee->last_name) }}" placeholder="Enter last name" required>
                                    <div class="invalid-feedback" id="last_name_error"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-semibold text-success">Extension Name</label>
                                    <input type="text" class="form-control form-control-sm border-success" name="ext_name" value="{{ old('ext_name', $employee->ext_name) }}" placeholder="Enter extension name" maxlength="10">
                                    <div class="invalid-feedback" id="ext_name_error"></div>
                                </div>
                            </div>
                            
                            <!-- Third Row (All fields in one row) -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="small fw-semibold text-success">Sex <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm border-success" name="sex" required>
                                        <option value="">Select Sex</option>
                                        <option value="M" {{ old('sex', $employee->sex) == 'M' ? 'selected' : '' }}>Male</option>
                                        <option value="F" {{ old('sex', $employee->sex) == 'F' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    <div class="invalid-feedback" id="sex_error"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-semibold text-success">Prefix</label>
                                    <input type="text" class="form-control form-control-sm border-success" name="prefix" value="{{ old('prefix', $employee->prefix) }}" placeholder="Enter prefix" maxlength="10">
                                    <div class="invalid-feedback" id="prefix_error"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-semibold text-success">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control form-control-sm border-success" name="email" value="{{ old('email', $employee->user->email ?? '') }}" placeholder="Enter email address" required>
                                    <div class="invalid-feedback" id="email_error"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-semibold text-success">Contact Number</label>
                                    <input type="tel" class="form-control form-control-sm border-success" name="contact_num" value="{{ old('contact_num', $employee->contact_num) }}" placeholder="Enter contact number">
                                    <div class="invalid-feedback" id="contact_num_error"></div>
                                </div>
                            </div>
                            
                            <!-- Fifth Row -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="small fw-semibold text-success">Password</label>
                                    <input type="password" class="form-control form-control-sm border-success" name="password" placeholder="Enter new password (leave blank to keep current)">
                                    <div class="invalid-feedback" id="password_error"></div>
                                    <small class="form-text text-muted">Leave blank to keep current password</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-semibold text-success">Confirm Password</label>
                                    <input type="password" class="form-control form-control-sm border-success" name="password_confirmation" placeholder="Confirm new password">
                                    <div class="invalid-feedback" id="password_confirmation_error"></div>
                                </div>
                            </div>
                            
                            <!-- Status -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="small fw-semibold text-success">Status <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm border-success" name="emp_status" required>
                                        <option value="1" {{ old('emp_status', $employee->emp_status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('emp_status', $employee->emp_status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <div class="invalid-feedback" id="emp_status_error"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Position Information Section -->
                        <div class="mb-6">
                            <h6 class="fw-bold text-success mb-3">Primary Position Information</h6>
                            
                            <!-- First Row -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="small fw-semibold text-success">Position Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm border-success" name="position_name" value="{{ old('position_name', $employee->position_name) }}" placeholder="Enter position name" required>
                                    <div class="invalid-feedback" id="position_name_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-semibold text-success">Class</label>
                                    <select class="form-control form-control-sm border-success" name="class_id">
                                        <option value="">Select Class (Optional)</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id_class }}" {{ old('class_id', $employee->positions->where('is_primary', true)->first()?->class_id == $class->id_class ? 'selected' : '') }}>
                                                {{ $class->class_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="class_id_error"></div>
                                </div>
                            </div>
                            
                            <!-- Second Row -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="small fw-semibold text-success">Office</label>
                                    <select class="form-control form-control-sm border-success" name="office_id" id="edit_office_id">
                                        <option value="">Select Office (Optional)</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}" {{ old('office_id', $employee->positions->where('is_primary', true)->first()?->office_id == $office->id ? 'selected' : '') }}>
                                                {{ $office->office_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="office_id_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-semibold text-success">Division</label>
                                    <select class="form-control form-control-sm border-success" name="division_id" id="edit_division_id">
                                        <option value="">Select Division (Optional)</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id_division }}" {{ old('division_id', $employee->positions->where('is_primary', true)->first()?->division_id == $division->id_division ? 'selected' : '') }}>
                                                {{ $division->division_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="division_id_error"></div>
                                </div>
                            </div>
                            
                            <!-- Third Row -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="small fw-semibold text-success">Unit</label>
                                    <select class="form-control form-control-sm border-success" name="unit_id" id="edit_unit_id">
                                        <option value="">Select Unit (Optional)</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id', $employee->positions->where('is_primary', true)->first()?->unit_id == $unit->id ? 'selected' : '') }}>
                                                {{ $unit->unit_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="unit_id_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-semibold text-success">Subunit</label>
                                    <select class="form-control form-control-sm border-success" name="subunit_id" id="edit_subunit_id">
                                        <option value="">Select Subunit (Optional)</option>
                                        @foreach($subunits as $subunit)
                                            <option value="{{ $subunit->id_subunit }}" {{ old('subunit_id', $employee->positions->where('is_primary', true)->first()?->subunit_id == $subunit->id_subunit ? 'selected' : '') }}>
                                                {{ $subunit->subunit_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="subunit_id_error"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Positions Section -->
                        <div class="mb-6">
                            <h6 class="fw-bold text-success mb-3">Additional Positions</h6>
                            
                            <div id="edit_additionalPositionsContainer">
                                <!-- Existing additional positions will be loaded here -->
                                @foreach($employee->positions->where('is_primary', false) as $index => $position)
                                    <div class="border p-3 rounded mb-3 position-relative" data-position-index="{{ $loop->iteration }}">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-position-btn" aria-label="Remove"></button>
                                        
                                        <!-- First Row -->
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label class="small fw-semibold text-success">Position Name <span class="text-danger">*</span></label>
                                                <input type="hidden" name="additional_positions[{{ $index }}][id]" value="{{ $position->id }}">
                                                <input type="text" name="additional_positions[{{ $index }}][position_name]" class="form-control form-control-sm border-success additional-position-name" value="{{ $position->position_name }}" placeholder="Enter position name" required>
                                                <div class="invalid-feedback additional-position-error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small fw-semibold text-success">Class</label>
                                                <select name="additional_positions[{{ $index }}][class_id]" class="form-control form-control-sm border-success">
                                                    <option value="">Select Class</option>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id_class }}" {{ $position->class_id == $class->id_class ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!-- Second Row -->
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label class="small fw-semibold text-success">Office</label>
                                                <select name="additional_positions[{{ $index }}][office_id]" class="form-control form-control-sm border-success edit-additional-office">
                                                    <option value="">Select Office</option>
                                                    @foreach($offices as $office)
                                                        <option value="{{ $office->id }}" {{ $position->office_id == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small fw-semibold text-success">Division</label>
                                                <select name="additional_positions[{{ $index }}][division_id]" class="form-control form-control-sm border-success edit-additional-division">
                                                    <option value="">Select Division</option>
                                                    @foreach($divisions as $division)
                                                        <option value="{{ $division->id_division }}" {{ $position->division_id == $division->id_division ? 'selected' : '' }}>{{ $division->division_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!-- Third Row -->
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label class="small fw-semibold text-success">Unit</label>
                                                <select name="additional_positions[{{ $index }}][unit_id]" class="form-control form-control-sm border-success edit-additional-unit">
                                                    <option value="">Select Unit</option>
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->id }}" {{ $position->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small fw-semibold text-success">Subunit</label>
                                                <select name="additional_positions[{{ $index }}][subunit_id]" class="form-control form-control-sm border-success edit-additional-subunit">
                                                    <option value="">Select Subunit</option>
                                                    @foreach($subunits as $subunit)
                                                        <option value="{{ $subunit->id_subunit }}" {{ $position->subunit_id == $subunit->id_subunit ? 'selected' : '' }}>{{ $subunit->subunit_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <button type="button" id="edit_addPositionBtn" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i>Add Another Position
                            </button>
                        </div>
                        
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-gray-500 rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-[#1e6031] rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150" id="edit_saveEmployeeBtn">
                                <i class="fas fa-save me-1"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get form elements
        const form = document.getElementById('editEmployeeForm');
        const saveBtn = document.getElementById('edit_saveEmployeeBtn');
        
        // Use pre-loaded cascading data
        const cascadingData = @json($cascadingData);
        
        // Pre-select division, unit, and subunit based on the employee's primary position
        const primaryPosition = @json($employee->positions->where('is_primary', true)->first());
        
        // Set initial selections based on primary position
        if (primaryPosition) {
            // Set office selection and trigger division load
            const officeSelect = document.getElementById('edit_office_id');
            if (officeSelect && primaryPosition.office_id) {
                officeSelect.value = primaryPosition.office_id;
                // Trigger change to populate divisions
                const event = new Event('change');
                officeSelect.dispatchEvent(event);
            }
            
            // Set division selection and trigger unit load
            const divisionSelect = document.getElementById('edit_division_id');
            if (divisionSelect && primaryPosition.division_id) {
                divisionSelect.value = primaryPosition.division_id;
                // Trigger change to populate units
                const event = new Event('change');
                divisionSelect.dispatchEvent(event);
            }
            
            // Set unit selection and trigger subunit load
            const unitSelect = document.getElementById('edit_unit_id');
            if (unitSelect && primaryPosition.unit_id) {
                unitSelect.value = primaryPosition.unit_id;
                // Trigger change to populate subunits
                const event = new Event('change');
                unitSelect.dispatchEvent(event);
            }
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
        let editPositionCounter = @json($employee->positions->where('is_primary', false)->count());
        
        if (document.getElementById('edit_addPositionBtn')) {
            document.getElementById('edit_addPositionBtn').addEventListener('click', function() {
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
                            <input type="text" name="additional_positions[\${editPositionCounter}][position_name]" class="form-control form-control-sm border-success additional-position-name" placeholder="Enter position name" required>
                            <div class="invalid-feedback additional-position-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-semibold text-success">Class</label>
                            <select name="additional_positions[\${editPositionCounter}][class_id]" class="form-control form-control-sm border-success">
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
                            <select name="additional_positions[\${editPositionCounter}][office_id]" class="form-control form-control-sm border-success edit-additional-office">
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}">{{ addslashes($office->office_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-semibold text-success">Division</label>
                            <select name="additional_positions[\${editPositionCounter}][division_id]" class="form-control form-control-sm border-success edit-additional-division">
                                <option value="">Select Division</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id_division }}">{{ addslashes($division->division_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Third Row -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="small fw-semibold text-success">Unit</label>
                            <select name="additional_positions[\${editPositionCounter}][unit_id]" class="form-control form-control-sm border-success edit-additional-unit">
                                <option value="">Select Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ addslashes($unit->unit_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-semibold text-success">Subunit</label>
                            <select name="additional_positions[\${editPositionCounter}][subunit_id]" class="form-control form-control-sm border-success edit-additional-subunit">
                                <option value="">Select Subunit</option>
                                @foreach($subunits as $subunit)
                                    <option value="{{ $subunit->id_subunit }}">{{ addslashes($subunit->subunit_name) }}</option>
                                @endforeach
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
            
            if (passwordInput && confirmPasswordInput && passwordInput.value !== '') {
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
                            }).then(() => location.reload());
                        });
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
                    saveBtn.innerHTML = '<i class="fas fa-save me-1"></i>Save Changes';
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
            const additionalPositionInputs = document.querySelectorAll('#edit_additionalPositionsContainer input[name*="[position_name]"]');
            additionalPositionInputs.forEach((input, index) => {
                if (input.value.trim() === '') {
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
    });
    </script>
</x-admin-layout>