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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="employeeForm" action="{{ route('admin.employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Display validation errors -->
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $employee->first_name) }}" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    </div>
                                    
                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $employee->last_name) }}" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div>
                                        <label for="middle_initial" class="block text-sm font-medium text-gray-700 mb-1">Middle Initial</label>
                                        <input type="text" name="middle_initial" id="middle_initial" value="{{ old('middle_initial', $employee->middle_initial) }}" maxlength="10"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    </div>
                                    
                                    <div>
                                        <label for="ext_name" class="block text-sm font-medium text-gray-700 mb-1">Extension Name</label>
                                        <input type="text" name="ext_name" id="ext_name" value="{{ old('ext_name', $employee->ext_name) }}" maxlength="10"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    </div>
                                    
                                    <div>
                                        <label for="sex" class="block text-sm font-medium text-gray-700 mb-1">Sex *</label>
                                        <select name="sex" id="sex" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Sex</option>
                                            <option value="M" {{ old('sex', $employee->sex) == 'M' ? 'selected' : '' }}>Male</option>
                                            <option value="F" {{ old('sex', $employee->sex) == 'F' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="prefix" class="block text-sm font-medium text-gray-700 mb-1">Prefix</label>
                                    <input type="text" name="prefix" id="prefix" value="{{ old('prefix', $employee->prefix) }}"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                </div>
                                
                                <!-- Login Credentials -->
                                <div class="mt-4">
                                    <h4 class="text-md font-medium text-gray-800 mb-2">Login Credentials</h4>
                                    
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $employee->user->email ?? '') }}" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                        <input type="password" name="password" id="password"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password</p>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Employment Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Employment Information</h3>
                                
                                <div>
                                    <label for="position_name" class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                                    <input type="text" name="position_name" id="position_name" value="{{ old('position_name', $employee->position_name) }}"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                </div>

                                <div class="mt-4">
                                    <label for="position_role" class="block text-sm font-medium text-gray-700 mb-1">Role in Position</label>
                                    <select name="position_role" id="position_role"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <option value="none" {{ old('position_role', $employee->positions->firstWhere('is_primary', true)->is_unit_head || $employee->positions->firstWhere('is_primary', true)->is_division_head || $employee->positions->firstWhere('is_primary', true)->is_vp || $employee->positions->firstWhere('is_primary', true)->is_president ? '' : 'selected') }}>Regular Employee</option>
                                        <option value="unit_head" {{ old('position_role', $employee->positions->firstWhere('is_primary', true)->is_unit_head ? 'unit_head' : '') == 'unit_head' ? 'selected' : '' }}>Unit Head</option>
                                        <option value="division_head" {{ old('position_role', $employee->positions->firstWhere('is_primary', true)->is_division_head ? 'division_head' : '') == 'division_head' ? 'selected' : '' }}>Division Head</option>
                                        <option value="vp" {{ old('position_role', $employee->positions->firstWhere('is_primary', true)->is_vp ? 'vp' : '') == 'vp' ? 'selected' : '' }}>VP</option>
                                        <option value="president" {{ old('position_role', $employee->positions->firstWhere('is_primary', true)->is_president ? 'president' : '') == 'president' ? 'selected' : '' }}>President</option>
                                    </select>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                                    <select name="class_id" id="class_id"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id', $employee->class_id) == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="office_id" class="block text-sm font-medium text-gray-700 mb-1">Office</label>
                                    <select name="office_id" id="office_id"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <option value="">Select Office</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}" {{ old('office_id', $employee->office_id) == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                                    <select name="division_id" id="division_id"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <option value="">Select Division</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}" {{ old('division_id', $employee->division_id) == $division->id ? 'selected' : '' }}>{{ $division->division_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                                    <select name="unit_id" id="unit_id"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <option value="">Select Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id', $employee->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="subunit_id" class="block text-sm font-medium text-gray-700 mb-1">Subunit</label>
                                    <select name="subunit_id" id="subunit_id"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <option value="">Select Subunit</option>
                                        @foreach($subunits as $subunit)
                                            <option value="{{ $subunit->id }}" {{ old('subunit_id', $employee->subunit_id) == $subunit->id ? 'selected' : '' }}>{{ $subunit->subunit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                                        
                                <!-- Additional Positions -->
                                <div id="additionalPositionsSection">
                                    <h3 class="text-lg font-medium text-gray-900 mt-4 mb-4">Additional Positions</h3>
                                                            
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

                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Role in Position</label>
                                                                <select name="additional_positions[{{ $loop->iteration }}][position_role]" 
                                                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                                                    <option value="none" {{ $position->is_unit_head || $position->is_division_head || $position->is_vp || $position->is_president ? '' : 'selected' }}>Regular Employee</option>
                                                                    <option value="unit_head" {{ $position->is_unit_head ? 'selected' : '' }}>Unit Head</option>
                                                                    <option value="division_head" {{ $position->is_division_head ? 'selected' : '' }}>Division Head</option>
                                                                    <option value="vp" {{ $position->is_vp ? 'selected' : '' }}>VP</option>
                                                                    <option value="president" {{ $position->is_president ? 'selected' : '' }}>President</option>
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
                                                        
                                <!-- Hidden input to always set emp_status to active (1) -->
                                <input type="hidden" name="emp_status" value="1">
                            </div>
                                                    
                            <!-- Status and Roles -->
                            
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('admin.employees.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                Cancel
                            </a>
                            <button type="submit" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-4 py-2 rounded-lg transition duration-300">
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
            
            // Load divisions when office is selected
            officeSelect.addEventListener('change', function() {
                const officeId = this.value;
                divisionSelect.innerHTML = '<option value="">Select Division</option>';
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                
                if (officeId) {
                    fetch('{{ route('admin.employees.get-divisions-by-office') }}?office_id=' + officeId)
                        .then(response => response.json())
                        .then(divisions => {
                            divisions.forEach(division => {
                                divisionSelect.innerHTML += '<option value="' + division.id + '">' + division.division_name + '</option>';
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
            
            // Load units when division is selected
            divisionSelect.addEventListener('change', function() {
                const divisionId = this.value;
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                
                if (divisionId) {
                    fetch('{{ route('admin.employees.get-units-by-division') }}?division_id=' + divisionId)
                        .then(response => response.json())
                        .then(units => {
                            units.forEach(unit => {
                                unitSelect.innerHTML += '<option value="' + unit.id + '">' + unit.unit_name + '</option>';
                            });
                        })
                        .catch(error => console.error('Error:', error));
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
                                subunitSelect.innerHTML += '<option value="' + subunit.id + '">' + subunit.subunit_name + '</option>';
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

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

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role in Position</label>
                            <select name="additional_positions[` + positionCounter + `][position_role]" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                <option value="none">Regular Employee</option>
                                <option value="unit_head">Unit Head</option>
                                <option value="division_head">Division Head</option>
                                <option value="vp">VP</option>
                                <option value="president">President</option>
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
                        fetch('{{ route('admin.employees.get-divisions-by-office') }}?office_id=' + officeId)
                            .then(response => response.json())
                            .then(divisions => {
                                divisions.forEach(division => {
                                    divisionSelect.innerHTML += '<option value="' + division.id + '">' + division.division_name + '</option>';
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
                        fetch('{{ route('admin.employees.get-units-by-division') }}?division_id=' + divisionId)
                            .then(response => response.json())
                            .then(units => {
                                units.forEach(unit => {
                                    unitSelect.innerHTML += '<option value="' + unit.id + '">' + unit.unit_name + '</option>';
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
                        fetch('{{ route('admin.employees.get-subunits-by-unit') }}?unit_id=' + unitId)
                            .then(response => response.json())
                            .then(subunits => {
                                subunits.forEach(subunit => {
                                    subunitSelect.innerHTML += '<option value="' + subunit.id + '">' + subunit.subunit_name + '</option>';
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