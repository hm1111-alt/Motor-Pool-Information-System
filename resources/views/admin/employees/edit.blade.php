<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <svg class="inline-block h-6 w-6 text-[#1e6031] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                {{ __('Edit Employee') }}
            </h2>
            <a href="{{ route('admin.employees.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Employees
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="employeeForm" action="{{ route('admin.employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Personal Information Section -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $employee->first_name) }}" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        @error('first_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="middle_initial" class="block text-sm font-medium text-gray-700 mb-1">Middle Initial</label>
                                        <input type="text" name="middle_initial" id="middle_initial" value="{{ old('middle_initial', $employee->middle_initial) }}" maxlength="10"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        @error('middle_initial')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $employee->last_name) }}" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        @error('last_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="ext_name" class="block text-sm font-medium text-gray-700 mb-1">Extension Name</label>
                                        <input type="text" name="ext_name" id="ext_name" value="{{ old('ext_name', $employee->ext_name) }}" maxlength="10"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        @error('ext_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="sex" class="block text-sm font-medium text-gray-700 mb-1">Sex *</label>
                                        <select name="sex" id="sex" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Sex</option>
                                            <option value="M" {{ old('sex', $employee->sex) == 'M' ? 'selected' : '' }}>Male</option>
                                            <option value="F" {{ old('sex', $employee->sex) == 'F' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('sex')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="prefix" class="block text-sm font-medium text-gray-700 mb-1">Prefix</label>
                                        <input type="text" name="prefix" id="prefix" value="{{ old('prefix', $employee->prefix) }}" maxlength="10"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        @error('prefix')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $employee->user->email ?? '') }}" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        @error('email')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                        <input type="password" name="password" id="password"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password</p>
                                        @error('password')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        @error('password_confirmation')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="contact_num" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                                        <input type="tel" name="contact_num" id="contact_num" value="{{ old('contact_num', $employee->contact_num) }}"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        @error('contact_num')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="emp_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select name="emp_status" id="emp_status" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="1" {{ old('emp_status', $employee->emp_status) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('emp_status', $employee->emp_status) == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('emp_status')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Primary Position Information Section -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Primary Position Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="position_name" class="block text-sm font-medium text-gray-700 mb-1">Position Name</label>
                                        <input type="text" name="position_name" id="position_name" value="{{ old('position_name', $employee->position_name) }}"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                        @error('position_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>



                                    <div>
                                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Class (Optional)</label>
                                        <select name="class_id" id="class_id"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Class (Optional)</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id', $employee->class_id) == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="office_id" class="block text-sm font-medium text-gray-700 mb-1">Office (Optional)</label>
                                        <select name="office_id" id="office_id"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Office (Optional)</option>
                                            @foreach($offices as $office)
                                                <option value="{{ $office->id }}" {{ old('office_id', $employee->office_id) == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('office_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">Division (Optional)</label>
                                        <select name="division_id" id="division_id"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Division (Optional)</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}" {{ old('division_id', $employee->division_id) == $division->id ? 'selected' : '' }}>{{ $division->division_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('division_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">Unit (Optional)</label>
                                        <select name="unit_id" id="unit_id"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Unit (Optional)</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ old('unit_id', $employee->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('unit_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="subunit_id" class="block text-sm font-medium text-gray-700 mb-1">Subunit (Optional)</label>
                                        <select name="subunit_id" id="subunit_id"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Subunit (Optional)</option>
                                            @foreach($subunits as $subunit)
                                                <option value="{{ $subunit->id }}" {{ old('subunit_id', $employee->subunit_id) == $subunit->id ? 'selected' : '' }}>{{ $subunit->subunit_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('subunit_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                                                        
                            <!-- Additional Positions Section -->
                            <div class="md:col-span-2">
                                <div id="additionalPositionsSection">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Positions</h3>
                                                            
                                    <div id="additionalPositionsContainer">
                                        <!-- Dynamic positions will be added here -->
                                        @if($employee->positions->count() > 1)
                                            @foreach($employee->positions as $index => $position)
                                                @if(!$position->is_primary)
                                                    <div class="additional-position-group border p-4 rounded-lg mb-4" data-position-index="{{ $loop->iteration }}">
                                                        <div class="flex justify-between items-center mb-2">
                                                            <h4 class="text-md font-medium text-gray-800">Additional Position #{{ $loop->iteration }}</h4>
                                                            <button type="button" class="remove-position-btn bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded text-sm">Remove</button>
                                                        </div>
                                                                                
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Position Name</label>
                                                                <input type="text" name="additional_positions[{{ $loop->iteration }}][position_name]" 
                                                                    value="{{ $position->position_name }}"
                                                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                                                <input type="hidden" name="additional_positions[{{ $loop->iteration }}][id]" value="{{ $position->id }}">
                                                            </div>
                                                                                    
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                                                                <select name="additional_positions[{{ $loop->iteration }}][class_id]" 
                                                                    class="additional-class-select w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                                                    <option value="">Select Class</option>
                                                                    @foreach($classes as $class)
                                                                        <option value="{{ $class->id }}" {{ $position->class_id == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                                                    
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Office</label>
                                                                <select name="additional_positions[{{ $loop->iteration }}][office_id]" 
                                                                    class="additional-office-select w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                                                    <option value="">Select Office</option>
                                                                    @foreach($offices as $office)
                                                                        <option value="{{ $office->id }}" {{ $position->office_id == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                                                    
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                                                                <select name="additional_positions[{{ $loop->iteration }}][division_id]" 
                                                                    class="additional-division-select w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                                                    <option value="">Select Division</option>
                                                                    @foreach($divisions as $division)
                                                                        <option value="{{ $division->id }}" {{ $position->division_id == $division->id ? 'selected' : '' }}>{{ $division->division_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                                                    
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                                                                <select name="additional_positions[{{ $loop->iteration }}][unit_id]" 
                                                                    class="additional-unit-select w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                                                    <option value="">Select Unit</option>
                                                                    @foreach($units as $unit)
                                                                        <option value="{{ $unit->id }}" {{ $position->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                                                    
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Subunit</label>
                                                                <select name="additional_positions[{{ $loop->iteration }}][subunit_id]" 
                                                                    class="additional-subunit-select w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                                                    <option value="">Select Subunit</option>
                                                                    @foreach($subunits as $subunit)
                                                                        <option value="{{ $subunit->id }}" {{ $position->subunit_id == $subunit->id ? 'selected' : '' }}>{{ $subunit->subunit_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                        

                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                                            
                                    <button type="button" id="addPositionBtn" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                        Add Another Position
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.employees.index') }}" class="mr-3 bg-gray-500 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition duration-300">
                                Cancel
                            </a>
                            <button type="submit" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-6 py-3 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                                Update Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const officeSelect = document.getElementById('office_id');
            const divisionSelect = document.getElementById('division_id');
            const unitSelect = document.getElementById('unit_id');
            const subunitSelect = document.getElementById('subunit_id');
            
            // Store original options for reset
            const originalDivisionOptions = divisionSelect.innerHTML;
            const originalUnitOptions = unitSelect.innerHTML;
            const originalSubunitOptions = subunitSelect.innerHTML;
            
            // Load divisions when office is selected
            officeSelect.addEventListener('change', function() {
                const officeId = this.value;
                divisionSelect.innerHTML = '<option value="">Select Division</option>';
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';

                if (officeId) {
                    fetch('{{ route('admin.employees.get-divisions-by-office') }}?office_id=' + officeId, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(divisions => {
                            divisions.forEach(division => {
                                const selected = division.id_division == {{ $employee->division_id ?? 'null' }} ? 'selected' : '';
                                divisionSelect.innerHTML += '<option value="' + division.id_division + '" ' + selected + '>' + division.division_name + '</option>';
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            });
            
            // Load units when division is selected
            divisionSelect.addEventListener('change', function() {
                const divisionId = this.value;
                unitSelect.innerHTML = '<option value="">Loading units...</option>';
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';

                if (divisionId) {
                    fetch('{{ route('admin.employees.get-units-by-division') }}?division_id=' + divisionId, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(units => {
                            unitSelect.innerHTML = '<option value="">Select Unit</option>';
                            units.forEach(unit => {
                                const selected = unit.id_unit == {{ $employee->unit_id ?? 'null' }} ? 'selected' : '';
                                unitSelect.innerHTML += '<option value="' + unit.id_unit + '" ' + selected + '>' + unit.unit_name + '</option>';
                            });
                        })
                        .catch(error => {
                            unitSelect.innerHTML = '<option value="">Error loading units</option>';
                        });
                } else {
                    unitSelect.innerHTML = '<option value="">Select Unit</option>';
                }
            });
            
            // Load subunits when unit is selected
            unitSelect.addEventListener('change', function() {
                const unitId = this.value;
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                
                if (unitId) {
                    fetch('{{ route('admin.employees.get-subunits-by-unit') }}?unit_id=' + unitId)
                        .then(response => response.json())
                        .then(subunits => {
                            subunits.forEach(subunit => {
                                const selected = subunit.id == {{ $employee->subunit_id ?? 'null' }} ? 'selected' : '';
                                subunitSelect.innerHTML += '<option value="' + subunit.id + '" ' + selected + '>' + subunit.subunit_name + '</option>';
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
            
            // Initialize cascading dropdowns on page load if values exist
            function initializeCascadingDropdowns() {
                const officeId = officeSelect.value;
                const divisionId = divisionSelect.value;
                const unitId = unitSelect.value;
                
                // If office is selected, load divisions
                if (officeId) {
                    fetch('{{ route('admin.employees.get-divisions-by-office') }}?office_id=' + officeId, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(divisions => {
                            let divisionHtml = '<option value="">Select Division</option>';
                            divisions.forEach(division => {
                                const selected = division.id_division == {{ $employee->division_id ?? 'null' }} ? 'selected' : '';
                                divisionHtml += '<option value="' + division.id_division + '" ' + selected + '>' + division.division_name + '</option>';
                            });
                            divisionSelect.innerHTML = divisionHtml;
                        })
                        .catch(error => console.error('Error:', error));
                }
                
                // If division is selected, load units
                if (divisionId) {
                    fetch('{{ route('admin.employees.get-units-by-division') }}?division_id=' + divisionId, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(units => {
                            let unitHtml = '<option value="">Select Unit</option>';
                            units.forEach(unit => {
                                const selected = unit.id_unit == {{ $employee->unit_id ?? 'null' }} ? 'selected' : '';
                                unitHtml += '<option value="' + unit.id_unit + '" ' + selected + '>' + unit.unit_name + '</option>';
                            });
                            unitSelect.innerHTML = unitHtml;
                        })
                        .catch(error => console.error('Error:', error));
                }
                
                // If unit is selected, load subunits
                if (unitId) {
                    fetch('{{ route('admin.employees.get-subunits-by-unit') }}?unit_id=' + unitId, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(subunits => {
                            let subunitHtml = '<option value="">Select Subunit</option>';
                            subunits.forEach(subunit => {
                                const selected = subunit.id_subunit == {{ $employee->subunit_id ?? 'null' }} ? 'selected' : '';
                                subunitHtml += '<option value="' + subunit.id_subunit + '" ' + selected + '>' + subunit.subunit_name + '</option>';
                            });
                            subunitSelect.innerHTML = subunitHtml;
                        })
                        .catch(error => console.error('Error:', error));
                }
            }
            
            // Initialize on page load
            initializeCascadingDropdowns();

            // Handle additional positions
            let positionCounter = {{ $employee->positions->where('is_primary', false)->count() > 0 ? $employee->positions->where('is_primary', false)->count() : 0 }};

            document.getElementById('addPositionBtn').addEventListener('click', function() {
                positionCounter++;
                const container = document.getElementById('additionalPositionsContainer');

                const positionDiv = document.createElement('div');
                positionDiv.className = 'additional-position-group border p-4 rounded-lg mb-4';
                positionDiv.dataset.positionIndex = positionCounter;
                positionDiv.innerHTML = `
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="text-md font-medium text-gray-800">Additional Position #` + positionCounter + `</h4>
                        <button type="button" class="remove-position-btn bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded text-sm">Remove</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position Name</label>
                            <input type="text" name="additional_positions[` + positionCounter + `][position_name]" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                            <select name="additional_positions[` + positionCounter + `][class_id]" 
                                class="additional-class-select w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Office</label>
                            <select name="additional_positions[` + positionCounter + `][office_id]" 
                                class="additional-office-select w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                            <select name="additional_positions[` + positionCounter + `][division_id]" 
                                class="additional-division-select w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                <option value="">Select Division</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select name="additional_positions[` + positionCounter + `][unit_id]" 
                                class="additional-unit-select w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                <option value="">Select Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subunit</label>
                            <select name="additional_positions[` + positionCounter + `][subunit_id]" 
                                class="additional-subunit-select w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                <option value="">Select Subunit</option>
                                @foreach($subunits as $subunit)
                                    <option value="{{ $subunit->id }}">{{ $subunit->subunit_name }}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                `;

                container.appendChild(positionDiv);

                // Add event listeners to the new dropdowns
                attachDropdownEventListeners(positionDiv);

                // Add event listener to the remove button
                positionDiv.querySelector('.remove-position-btn').addEventListener('click', function() {
                    positionDiv.remove();
                });
            });

            // Function to attach dropdown event listeners to a position group
            function attachDropdownEventListeners(group) {
                const officeSelect = group.querySelector('.additional-office-select');
                const divisionSelect = group.querySelector('.additional-division-select');
                const unitSelect = group.querySelector('.additional-unit-select');
                const subunitSelect = group.querySelector('.additional-subunit-select');

                // Load divisions when office is selected for this group
                officeSelect.addEventListener('change', function() {
                    const officeId = this.value;
                    divisionSelect.innerHTML = '<option value="">Select Division</option>';
                    unitSelect.innerHTML = '<option value="">Select Unit</option>';
                    subunitSelect.innerHTML = '<option value="">Select Subunit</option>';

                    if (officeId) {
                        fetch('{{ route('admin.employees.get-divisions-by-office') }}?office_id=' + officeId, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(divisions => {
                                divisions.forEach(division => {
                                    divisionSelect.innerHTML += '<option value="' + division.id_division + '">' + division.division_name + '</option>';
                                });
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });

                // Load units when division is selected for this group
                divisionSelect.addEventListener('change', function() {
                    const divisionId = this.value;
                    unitSelect.innerHTML = '<option value="">Select Unit</option>';
                    subunitSelect.innerHTML = '<option value="">Select Subunit</option>';

                    if (divisionId) {
                        fetch('{{ route('admin.employees.get-units-by-division') }}?division_id=' + divisionId, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(units => {
                                units.forEach(unit => {
                                    unitSelect.innerHTML += '<option value="' + unit.id_unit + '">' + unit.unit_name + '</option>';
                                });
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });

                // Load subunits when unit is selected for this group
                unitSelect.addEventListener('change', function() {
                    const unitId = this.value;
                    subunitSelect.innerHTML = '<option value="">Select Subunit</option>';

                    if (unitId) {
                        fetch('{{ route('admin.employees.get-subunits-by-unit') }}?unit_id=' + unitId, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(subunits => {
                                subunits.forEach(subunit => {
                                    subunitSelect.innerHTML += '<option value="' + subunit.id_subunit + '">' + subunit.subunit_name + '</option>';
                                });
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            }

            // Add event listeners to existing additional positions' remove buttons
            document.querySelectorAll('.remove-position-btn').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.additional-position-group').remove();
                });
            });
        });
    </script>
</x-app-layout>