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
                                        <input type="text" name="middle_initial" id="middle_initial" value="{{ old('middle_initial', $employee->middle_initial) }}"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    </div>
                                    
                                    <div>
                                        <label for="ext_name" class="block text-sm font-medium text-gray-700 mb-1">Extension Name</label>
                                        <input type="text" name="ext_name" id="ext_name" value="{{ old('ext_name', $employee->ext_name) }}"
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
                                    <label for="prefix" class="block text-sm font-medium text-gray-700 mb-1">Prefix (e.g., Dr., Engr.)</label>
                                    <input type="text" name="prefix" id="prefix" value="{{ old('prefix', $employee->prefix) }}"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                </div>
                            </div>
                            
                            <!-- Employment Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Employment Information</h3>
                                
                                <div>
                                    <label for="position_name" class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                                    <input type="text" name="position_name" id="position_name" value="{{ old('position_name', $employee->position_name) }}" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                </div>
                                
                                <div class="mt-4">
                                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Class *</label>
                                    <select name="class_id" id="class_id" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id', $employee->class_id) == $class->id ? 'selected' : '' }}>
                                                {{ $class->class_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="office_id" class="block text-sm font-medium text-gray-700 mb-1">Office *</label>
                                    <select name="office_id" id="office_id" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <option value="">Select Office</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}" {{ old('office_id', $employee->office_id) == $office->id ? 'selected' : '' }}>
                                                {{ $office->office_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">Division *</label>
                                    <select name="division_id" id="division_id" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <option value="">Select Division</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}" {{ old('division_id', $employee->division_id) == $division->id ? 'selected' : '' }}>
                                                {{ $division->division_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                                    <select name="unit_id" id="unit_id" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <option value="">Select Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id', $employee->unit_id) == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->unit_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="subunit_id" class="block text-sm font-medium text-gray-700 mb-1">Subunit *</label>
                                    <select name="subunit_id" id="subunit_id" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        <option value="">Select Subunit</option>
                                        @foreach($subunits as $subunit)
                                            <option value="{{ $subunit->id }}" {{ old('subunit_id', $employee->subunit_id) == $subunit->id ? 'selected' : '' }}>
                                                {{ $subunit->subunit_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Status and Roles -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Status and Roles</h3>
                                
                                <div class="flex items-center">
                                    <input type="hidden" name="emp_status" value="0">
                                    <input type="checkbox" name="emp_status" id="emp_status" value="1" {{ old('emp_status', $employee->emp_status) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-[#1e6031] shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <label for="emp_status" class="ml-2 block text-sm text-gray-900">Active</label>
                                </div>
                                
                                <div class="flex items-center mt-2">
                                    <input type="hidden" name="is_divisionhead" value="0">
                                    <input type="checkbox" name="is_divisionhead" id="is_divisionhead" value="1" {{ old('is_divisionhead', $employee->is_divisionhead) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-[#1e6031] shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <label for="is_divisionhead" class="ml-2 block text-sm text-gray-900">Division Head</label>
                                </div>
                                
                                <div class="flex items-center mt-2">
                                    <input type="hidden" name="is_vp" value="0">
                                    <input type="checkbox" name="is_vp" id="is_vp" value="1" {{ old('is_vp', $employee->is_vp) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-[#1e6031] shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <label for="is_vp" class="ml-2 block text-sm text-gray-900">VP</label>
                                </div>
                            </div>
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
                    fetch(`{{ route('admin.employees.get-divisions-by-office') }}?office_id=${officeId}`)
                        .then(response => response.json())
                        .then(divisions => {
                            divisions.forEach(division => {
                                divisionSelect.innerHTML += `<option value="${division.id}">${division.division_name}</option>`;
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
                    fetch(`{{ route('admin.employees.get-units-by-division') }}?division_id=${divisionId}`)
                        .then(response => response.json())
                        .then(units => {
                            units.forEach(unit => {
                                unitSelect.innerHTML += `<option value="${unit.id}">${unit.unit_name}</option>`;
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
                    fetch(`{{ route('admin.employees.get-subunits-by-unit') }}?unit_id=${unitId}`)
                        .then(response => response.json())
                        .then(subunits => {
                            subunits.forEach(subunit => {
                                subunitSelect.innerHTML += `<option value="${subunit.id}">${subunit.subunit_name}</option>`;
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>
</x-app-layout>