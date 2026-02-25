@if($employee)
    <form method="post" action="{{ route('profile.update') }}" id="employeeProfileForm">
        @csrf
        @method('patch')
        
        <!-- Form Body with Bootstrap-style inputs to match modal design -->
        <div class="modal-body px-3 py-2">
            <!-- Personal Information Section -->
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $employee->first_name) }}" required
                    class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                @error('first_name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $employee->last_name) }}" required
                    class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                @error('last_name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Middle Initial</label>
                <input type="text" name="middle_initial" id="middle_initial" value="{{ old('middle_initial', $employee->middle_initial) }}" maxlength="10"
                    class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                @error('middle_initial')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Extension Name</label>
                <input type="text" name="ext_name" id="ext_name" value="{{ old('ext_name', $employee->ext_name) }}" maxlength="10"
                    class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                @error('ext_name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Prefix</label>
                <input type="text" name="prefix" id="prefix" value="{{ old('prefix', $employee->prefix) }}" maxlength="10"
                    class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                @error('prefix')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Sex <span class="text-danger">*</span></label>
                <select name="sex" id="sex" required
                    class="form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                    <option value="">Select Sex</option>
                    <option value="M" {{ old('sex', $employee->sex) == 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ old('sex', $employee->sex) == 'F' ? 'selected' : '' }}>Female</option>
                </select>
                @error('sex')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Contact Number</label>
                <input type="tel" name="contact_num" id="contact_num" value="{{ old('contact_num', $employee->contact_num) }}"
                    class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                @error('contact_num')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <hr class="my-3">
            
            <!-- Primary Position Information Section -->
            <h4 class="fw-bold text-success mb-2" style="font-size: 0.9rem;">Primary Position</h4>
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Position Name <span class="text-danger">*</span></label>
                <input type="text" name="position_name" id="position_name" value="{{ old('position_name', $primaryPosition?->position_name) }}" required
                    class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                @error('position_name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Class (Optional)</label>
                <select name="class_id" id="class_id"
                    class="form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                    <option value="">Select Class (Optional)</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $primaryPosition?->class_id) == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                    @endforeach
                </select>
                @error('class_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Office (Optional)</label>
                <select name="office_id" id="office_id"
                    class="form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                    <option value="">Select Office (Optional)</option>
                    @foreach($offices as $office)
                        <option value="{{ $office->id }}" {{ old('office_id', $primaryPosition?->office_id) == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                    @endforeach
                </select>
                @error('office_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Division (Optional)</label>
                <select name="division_id" id="division_id"
                    class="form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" disabled>
                    <option value="">Select Division (Optional)</option>
                </select>
                @error('division_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Unit (Optional)</label>
                <select name="unit_id" id="unit_id"
                    class="form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" disabled>
                    <option value="">Select Unit (Optional)</option>
                </select>
                @error('unit_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Subunit (Optional)</label>
                <select name="subunit_id" id="subunit_id"
                    class="form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" disabled>
                    <option value="">Select Subunit (Optional)</option>
                </select>
                @error('subunit_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <hr class="my-3">
            
            <!-- Additional Positions Section -->
            <h4 class="fw-bold text-success mb-2" style="font-size: 0.9rem;">Additional Positions</h4>
            <div id="additional-positions-container">
                @forelse($allPositions->where('is_primary', false) as $index => $position)
                <div class="position-group mb-3 p-3 border rounded" style="background-color: #f8f9fa;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-semibold text-dark mb-0" style="font-size: 0.9rem;">Position #{{ $index + 1 }}</h5>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-position-btn" style="font-size: 0.75rem; height: 25px; padding: 0 0.5rem;">
                            Remove
                        </button>
                    </div>
                    
                    <input type="hidden" name="additional_positions[{{ $index }}][id]" value="{{ $position->id }}">
                    
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Position Name</label>
                        <input type="text" name="additional_positions[{{ $index }}][position_name]" value="{{ old("additional_positions.{$index}.position_name", $position->position_name) }}"
                            class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Class (Optional)</label>
                        <select name="additional_positions[{{ $index }}][class_id]"
                            class="form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                            <option value="">Select Class (Optional)</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old("additional_positions.{$index}.class_id", $position->class_id) == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Office (Optional)</label>
                        <select name="additional_positions[{{ $index }}][office_id]" class="position-office-select form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                            <option value="">Select Office (Optional)</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}" {{ old("additional_positions.{$index}.office_id", $position->office_id) == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Division (Optional)</label>
                        <select name="additional_positions[{{ $index }}][division_id]" class="position-division-select form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" disabled>
                            <option value="">Select Division (Optional)</option>

                        </select>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Unit (Optional)</label>
                        <select name="additional_positions[{{ $index }}][unit_id]" class="position-unit-select form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" disabled>
                            <option value="">Select Unit (Optional)</option>

                        </select>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Subunit (Optional)</label>
                        <select name="additional_positions[{{ $index }}][subunit_id]" class="form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" disabled>
                            <option value="">Select Subunit (Optional)</option>

                        </select>
                    </div>
                </div>
                @empty
                <div class="text-muted" style="font-size: 0.85rem;">No additional positions. Click "Add Position" to add one.</div>
                @endforelse
            </div>
            
            <div class="mb-3">
                <button type="button" id="add-position-btn" class="btn btn-sm btn-outline-success" style="font-size: 0.75rem; height: 30px;">
                    + Add Position
                </button>
            </div>
        </div>

        <!-- Form Footer with Bootstrap-style buttons -->
        <div class="modal-footer py-1 justify-content-end">
            <button type="submit" class="btn btn-sm btn-success py-1" style="font-size: 0.75rem; height: 30px;">
                Save
            </button>
            
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success mt-2" role="alert" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                    {{ __('Profile updated successfully!') }}
                </div>
            @endif
        </div>
    </form>
    
    <!-- Position Template for Adding New Positions -->
    <template id="position-template">
        <div class="position-group mb-3 p-3 border rounded" style="background-color: #f8f9fa;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="fw-semibold text-dark mb-0" style="font-size: 0.9rem;">New Position</h5>
                <button type="button" class="btn btn-sm btn-outline-danger remove-position-btn" style="font-size: 0.75rem; height: 25px; padding: 0 0.5rem;">
                    Remove
                </button>
            </div>
            
            <input type="hidden" name="additional_positions[__INDEX__][id]" value="">
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Position Name</label>
                <input type="text" name="additional_positions[__INDEX__][position_name]" value=""
                    class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Class (Optional)</label>
                <select name="additional_positions[__INDEX__][class_id]"
                    class="form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                    <option value="">Select Class (Optional)</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Office (Optional)</label>
                <select name="additional_positions[__INDEX__][office_id]" class="position-office-select form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
                    <option value="">Select Office (Optional)</option>
                    @foreach($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Division (Optional)</label>
                <select name="additional_positions[__INDEX__][division_id]" class="position-division-select form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" disabled>
                    <option value="">Select Division (Optional)</option>
                </select>
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Unit (Optional)</label>
                <select name="additional_positions[__INDEX__][unit_id]" class="position-unit-select form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" disabled>
                    <option value="">Select Unit (Optional)</option>
                </select>
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Subunit (Optional)</label>
                <select name="additional_positions[__INDEX__][subunit_id]" class="form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" disabled>
                    <option value="">Select Subunit (Optional)</option>
                </select>
            </div>
        </div>
    </template>
    
    <!-- JavaScript for Dynamic Positions and Cascading Dropdowns -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize primary position dropdowns with existing data
            function initializePrimaryPositionDropdowns() {
                const officeId = {{ $primaryPosition->office_id ?? 'null' }};
                const divisionId = {{ $primaryPosition->division_id ?? 'null' }};
                const unitId = {{ $primaryPosition->unit_id ?? 'null' }};
                const subunitId = {{ $primaryPosition->subunit_id ?? 'null' }};
                
                const officeSelect = document.getElementById('office_id');
                const divisionSelect = document.getElementById('division_id');
                const unitSelect = document.getElementById('unit_id');
                const subunitSelect = document.getElementById('subunit_id');
                
                const cascadingData = @json($cascadingData);
                
                // Set office selection and populate divisions
                if (officeId && officeSelect) {
                    officeSelect.value = officeId;
                    
                    // Populate divisions for selected office
                    if (cascadingData.divisions[officeId]) {
                        divisionSelect.innerHTML = '<option value="">Select Division (Optional)</option>';
                        cascadingData.divisions[officeId].forEach(division => {
                            const selected = division.id_division == divisionId ? 'selected' : '';
                            divisionSelect.innerHTML += '<option value="' + division.id_division + '" ' + selected + '>' + division.division_name + '</option>';
                        });
                        
                        // Ensure the correct division is selected after populating
                        if (divisionId) {
                            divisionSelect.value = divisionId;
                        }
                        divisionSelect.disabled = false;
                    }
                }
                
                // Set division selection and populate units
                if (divisionId && divisionSelect) {
                    divisionSelect.value = divisionId;
                    
                    // Populate units for selected division
                    if (cascadingData.units[divisionId]) {
                        unitSelect.innerHTML = '<option value="">Select Unit (Optional)</option>';
                        cascadingData.units[divisionId].forEach(unit => {
                            const selected = unit.id_unit == unitId ? 'selected' : '';
                            unitSelect.innerHTML += '<option value="' + unit.id_unit + '" ' + selected + '>' + unit.unit_name + '</option>';
                        });
                        
                        // Ensure the correct unit is selected after populating
                        if (unitId) {
                            unitSelect.value = unitId;
                        }
                        unitSelect.disabled = false;
                    }
                }
                
                // Set unit selection and populate subunits
                if (unitId && unitSelect) {
                    unitSelect.value = unitId;
                    
                    // Populate subunits for selected unit
                    if (cascadingData.subunits[unitId]) {
                        subunitSelect.innerHTML = '<option value="">Select Subunit (Optional)</option>';
                        cascadingData.subunits[unitId].forEach(subunit => {
                            const selected = subunit.id_subunit == subunitId ? 'selected' : '';
                            subunitSelect.innerHTML += '<option value="' + subunit.id_subunit + '" ' + selected + '>' + subunit.subunit_name + '</option>';
                        });
                        
                        // Ensure the correct subunit is selected after populating
                        if (subunitId) {
                            subunitSelect.value = subunitId;
                        }
                        subunitSelect.disabled = false;
                    }
                }
            }
            
            // Initialize additional positions dropdowns
            function initializeAdditionalPositions() {
                @foreach($allPositions->where('is_primary', false) as $index => $position)
                const positionGroup = document.querySelector('.position-group:nth-child({{ $loop->iteration }})');
                if (positionGroup) {
                    const officeSelect = positionGroup.querySelector('.position-office-select');
                    const divisionSelect = positionGroup.querySelector('.position-division-select');
                    const unitSelect = positionGroup.querySelector('.position-unit-select');
                    const subunitSelect = positionGroup.querySelector('.form-select[name*="[subunit_id]"]');
                    
                    const officeId = {{ $position->office_id ?? 'null' }};
                    const divisionId = {{ $position->division_id ?? 'null' }};
                    const unitId = {{ $position->unit_id ?? 'null' }};
                    const subunitId = {{ $position->subunit_id ?? 'null' }};
                    
                    const cascadingData = @json($cascadingData);
                    
                    // Set office selection and populate divisions
                    if (officeId && officeSelect) {
                        officeSelect.value = officeId;
                        
                        // Populate divisions for selected office
                        if (cascadingData.divisions[officeId]) {
                            divisionSelect.innerHTML = '<option value="">Select Division (Optional)</option>';
                            cascadingData.divisions[officeId].forEach(division => {
                                const selected = division.id_division == divisionId ? 'selected' : '';
                                divisionSelect.innerHTML += '<option value="' + division.id_division + '" ' + selected + '>' + division.division_name + '</option>';
                            });
                            
                            // Ensure the correct division is selected after populating
                            if (divisionId) {
                                divisionSelect.value = divisionId;
                            }
                            divisionSelect.disabled = false;
                        }
                    }
                    
                    // Set division selection and populate units
                    if (divisionId && divisionSelect) {
                        divisionSelect.value = divisionId;
                        
                        // Populate units for selected division
                        if (cascadingData.units[divisionId]) {
                            unitSelect.innerHTML = '<option value="">Select Unit (Optional)</option>';
                            cascadingData.units[divisionId].forEach(unit => {
                                const selected = unit.id_unit == unitId ? 'selected' : '';
                                unitSelect.innerHTML += '<option value="' + unit.id_unit + '" ' + selected + '>' + unit.unit_name + '</option>';
                            });
                            
                            // Ensure the correct unit is selected after populating
                            if (unitId) {
                                unitSelect.value = unitId;
                            }
                            unitSelect.disabled = false;
                        }
                    }
                    
                    // Set unit selection and populate subunits
                    if (unitId && unitSelect) {
                        unitSelect.value = unitId;
                        
                        // Populate subunits for selected unit
                        if (cascadingData.subunits[unitId]) {
                            subunitSelect.innerHTML = '<option value="">Select Subunit (Optional)</option>';
                            cascadingData.subunits[unitId].forEach(subunit => {
                                const selected = subunit.id_subunit == subunitId ? 'selected' : '';
                                subunitSelect.innerHTML += '<option value="' + subunit.id_subunit + '" ' + selected + '>' + subunit.subunit_name + '</option>';
                            });
                            
                            // Ensure the correct subunit is selected after populating
                            if (subunitId) {
                                subunitSelect.value = subunitId;
                            }
                            subunitSelect.disabled = false;
                        }
                    }
                }
                @endforeach
            }
            
            // Initialize all dropdowns on page load
            initializePrimaryPositionDropdowns();
            initializeAdditionalPositions();
            
            let positionIndex = {{ count($allPositions->where('is_primary', false)) }};
            
            // Add position button functionality
            document.getElementById('add-position-btn').addEventListener('click', function() {
                const container = document.getElementById('additional-positions-container');
                const template = document.getElementById('position-template');
                const clone = template.content.cloneNode(true);
                
                // Update the index in the cloned element
                const positionGroup = clone.querySelector('.position-group');
                positionGroup.innerHTML = positionGroup.innerHTML.replace(/__INDEX__/g, positionIndex);
                
                container.appendChild(positionGroup);
                positionIndex++;
                
                // Add event listener to the new remove button
                const removeBtn = positionGroup.querySelector('.remove-position-btn');
                removeBtn.addEventListener('click', function() {
                    this.closest('.position-group').remove();
                });
                
                // Add event listeners for cascading dropdowns
                setupCascadingDropdowns(positionGroup);
            });
            
            // Remove position button functionality
            document.querySelectorAll('.remove-position-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    this.closest('.position-group').remove();
                });
            });
            
            // Setup cascading dropdowns for existing positions
            document.querySelectorAll('.position-group').forEach(setupCascadingDropdowns);
            
            // Setup cascading dropdowns for primary position
            setupCascadingDropdowns(document.body);
            
            function setupCascadingDropdowns(group) {
                // Check if this is the primary position (doesn't have the specific classes for additional positions)
                const isPrimaryPosition = !group.classList.contains('position-group');
                
                let officeSelect, divisionSelect, unitSelect;
                
                if (isPrimaryPosition) {
                    // For primary position, use the regular selects
                    officeSelect = document.getElementById('office_id');
                    divisionSelect = document.getElementById('division_id');
                    unitSelect = document.getElementById('unit_id');
                } else {
                    // For additional positions, use the class-based selectors
                    officeSelect = group.querySelector('.position-office-select');
                    divisionSelect = group.querySelector('.position-division-select');
                    unitSelect = group.querySelector('.position-unit-select');
                }
                
                if (officeSelect && divisionSelect) {
                    officeSelect.addEventListener('change', function() {
                        const officeId = this.value;
                        // Get divisions for the selected office
                        const cascadingData = @json($cascadingData);
                        const divisions = cascadingData.divisions[officeId] || [];
                        
                        divisionSelect.innerHTML = '<option value="">Select Division (Optional)</option>';
                        
                        divisions.forEach(function(division) {
                            const optionElement = document.createElement('option');
                            optionElement.value = division.id_division;
                            optionElement.textContent = division.division_name;
                            divisionSelect.appendChild(optionElement);
                        });
                        
                        divisionSelect.disabled = divisions.length === 0;
                        
                        // Clear dependent dropdowns
                        if (unitSelect) {
                            unitSelect.innerHTML = '<option value="">Select Unit (Optional)</option>';
                            unitSelect.disabled = true;
                            
                            // Also clear subunit
                            const subunitSelect = isPrimaryPosition ? document.getElementById('subunit_id') : group.querySelector('.form-select[name*="[subunit_id]"]');
                            if (subunitSelect) {
                                subunitSelect.innerHTML = '<option value="">Select Subunit (Optional)</option>';
                                subunitSelect.disabled = true;
                            }
                        }
                    });
                    
                    // Trigger change event if there's a pre-selected office to populate divisions
                    if (officeSelect.value) {
                        officeSelect.dispatchEvent(new Event('change'));
                    }
                }
                
                if (divisionSelect && unitSelect) {
                    divisionSelect.addEventListener('change', function() {
                        const divisionId = this.value;
                        // Get units for the selected division
                        const cascadingData = @json($cascadingData);
                        const units = cascadingData.units[divisionId] || [];
                        
                        unitSelect.innerHTML = '<option value="">Select Unit (Optional)</option>';
                        
                        units.forEach(function(unit) {
                            const optionElement = document.createElement('option');
                            optionElement.value = unit.id_unit;
                            optionElement.textContent = unit.unit_name;
                            unitSelect.appendChild(optionElement);
                        });
                        
                        unitSelect.disabled = units.length === 0;
                        
                        // Clear dependent dropdown
                        const subunitSelect = isPrimaryPosition ? document.getElementById('subunit_id') : group.querySelector('.form-select[name*="[subunit_id]"]');
                        if (subunitSelect) {
                            subunitSelect.innerHTML = '<option value="">Select Subunit (Optional)</option>';
                            subunitSelect.disabled = true;
                        }
                        
                        // Trigger change event if there's a pre-selected division to populate units
                        if (divisionSelect.value) {
                            divisionSelect.dispatchEvent(new Event('change'));
                        }
                    });
                    
                    // Trigger change event if there's a pre-selected division to populate units
                    if (divisionSelect.value) {
                        divisionSelect.dispatchEvent(new Event('change'));
                    }
                }
                
                if (unitSelect) {
                    unitSelect.addEventListener('change', function() {
                        const unitId = this.value;
                        const subunitSelect = isPrimaryPosition ? document.getElementById('subunit_id') : group.querySelector('.form-select[name*="[subunit_id]"]');
                        if (subunitSelect) {
                            // Get subunits for the selected unit
                            const cascadingData = @json($cascadingData);
                            const subunits = cascadingData.subunits[unitId] || [];
                            
                            subunitSelect.innerHTML = '<option value="">Select Subunit (Optional)</option>';
                            
                            subunits.forEach(function(subunit) {
                                const optionElement = document.createElement('option');
                                optionElement.value = subunit.id_subunit;
                                optionElement.textContent = subunit.subunit_name;
                                subunitSelect.appendChild(optionElement);
                            });
                            
                            subunitSelect.disabled = subunits.length === 0;
                        }
                        
                        // Trigger change event if there's a pre-selected unit to populate subunits
                        if (unitSelect.value) {
                            unitSelect.dispatchEvent(new Event('change'));
                        }
                    });
                    
                    // Trigger change event if there's a pre-selected unit to populate subunits
                    if (unitSelect.value) {
                        unitSelect.dispatchEvent(new Event('change'));
                    }
                }
            }
            

        });
    </script>
    
    @else
    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
            @error('email')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="modal-footer py-1 justify-content-end">
            <button type="submit" class="btn btn-sm btn-success py-1" style="font-size: 0.75rem; height: 30px;">
                Save
            </button>
            
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success mt-2" role="alert" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                    {{ __('Profile updated successfully!') }}
                </div>
            @endif
        </div>
    </form>
    @endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const officeSelect = document.getElementById('office_id');
    const divisionSelect = document.getElementById('division_id');
    const unitSelect = document.getElementById('unit_id');
    const subunitSelect = document.getElementById('subunit_id');
    
    // Use pre-loaded cascading data
    const cascadingData = @json($cascadingData);
    
    // Handle office change to load divisions
    officeSelect.addEventListener('change', function() {
        const officeId = this.value;
        divisionSelect.innerHTML = '<option value="">Select Division (Optional)</option>';
        unitSelect.innerHTML = '<option value="">Select Unit (Optional)</option>';
        subunitSelect.innerHTML = '<option value="">Select Subunit (Optional)</option>';
        
        if (officeId && cascadingData && cascadingData.divisions[officeId]) {
            const divisions = cascadingData.divisions[officeId];
            divisions.forEach(division => {
                const option = document.createElement('option');
                option.value = division.id_division;
                option.textContent = division.division_name;
                divisionSelect.appendChild(option);
            });
        }
        
        divisionSelect.disabled = !officeId;
        unitSelect.disabled = true;
        subunitSelect.disabled = true;
    });
    
    // Handle division change to load units
    divisionSelect.addEventListener('change', function() {
        const divisionId = this.value;
        unitSelect.innerHTML = '<option value="">Select Unit (Optional)</option>';
        subunitSelect.innerHTML = '<option value="">Select Subunit (Optional)</option>';
        
        if (divisionId && cascadingData && cascadingData.units) {
            // Find the units for this division across all offices
            for (const divId in cascadingData.units) {
                if (parseInt(divId) === parseInt(divisionId)) {
                    const units = cascadingData.units[divId];
                    units.forEach(unit => {
                        const option = document.createElement('option');
                        option.value = unit.id_unit;
                        option.textContent = unit.unit_name;
                        unitSelect.appendChild(option);
                    });
                    break;
                }
            }
        }
        
        unitSelect.disabled = !divisionId;
        subunitSelect.disabled = true;
    });
    
    // Handle unit change to load subunits
    unitSelect.addEventListener('change', function() {
        const unitId = this.value;
        subunitSelect.innerHTML = '<option value="">Select Subunit (Optional)</option>';
        
        if (unitId && cascadingData && cascadingData.subunits) {
            // Find the subunits for this unit across all divisions
            for (const uId in cascadingData.subunits) {
                if (parseInt(uId) === parseInt(unitId)) {
                    const subunits = cascadingData.subunits[uId];
                    subunits.forEach(subunit => {
                        const option = document.createElement('option');
                        option.value = subunit.id_subunit;
                        option.textContent = subunit.subunit_name;
                        subunitSelect.appendChild(option);
                    });
                    break;
                }
            }
        }
        
        subunitSelect.disabled = !unitId;
    });
    
    // Initialize dropdowns with existing data
    if (officeSelect.value) {
        officeSelect.dispatchEvent(new Event('change'));
        
        if (divisionSelect.value) {
            divisionSelect.dispatchEvent(new Event('change'));
            
            if (unitSelect.value) {
                unitSelect.dispatchEvent(new Event('change'));
            }
        }
    }
});
</script>