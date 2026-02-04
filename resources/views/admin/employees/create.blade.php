<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <svg class="inline-block h-6 w-6 text-[#1e6031] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                {{ __('Add New Employee') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.employees.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Employees
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="post" action="{{ route('admin.employees.store') }}" id="employeeForm">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Personal Information Section -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                        @error('first_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="middle_initial" class="block text-sm font-medium text-gray-700 mb-1">Middle Initial</label>
                                        <input type="text" name="middle_initial" id="middle_initial" value="{{ old('middle_initial') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" maxlength="10">
                                        @error('middle_initial')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                        @error('last_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="ext_name" class="block text-sm font-medium text-gray-700 mb-1">Extension Name</label>
                                        <input type="text" name="ext_name" id="ext_name" value="{{ old('ext_name') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" maxlength="10">
                                        @error('ext_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="sex" class="block text-sm font-medium text-gray-700 mb-1">Sex *</label>
                                        <select name="sex" id="sex" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                            <option value="">Select Sex</option>
                                            <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Male</option>
                                            <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('sex')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="prefix" class="block text-sm font-medium text-gray-700 mb-1">Prefix</label>
                                        <input type="text" name="prefix" id="prefix" value="{{ old('prefix') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" maxlength="10">
                                        @error('prefix')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                        @error('email')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                        <input type="password" name="password" id="password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                        @error('password')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                        @error('password_confirmation')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="contact_num" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                                        <input type="tel" name="contact_num" id="contact_num" value="{{ old('contact_num') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                        @error('contact_num')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Position Information Section -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Primary Position Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="position_name" class="block text-sm font-medium text-gray-700 mb-1">Position Name</label>
                                        <input type="text" name="position_name" id="position_name" value="{{ old('position_name') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                        @error('position_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>



                                    <div>
                                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Class (Optional)</label>
                                        <select name="class_id" id="class_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Class (Optional)</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="office_id" class="block text-sm font-medium text-gray-700 mb-1">Office (Optional)</label>
                                        <select name="office_id" id="office_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Office (Optional)</option>
                                            @foreach($offices as $office)
                                                <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('office_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">Division (Optional)</label>
                                        <select name="division_id" id="division_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Division (Optional)</option>
                                        </select>
                                        @error('division_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">Unit (Optional)</label>
                                        <select name="unit_id" id="unit_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Unit (Optional)</option>
                                        </select>
                                        @error('unit_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="subunit_id" class="block text-sm font-medium text-gray-700 mb-1">Subunit (Optional)</label>
                                        <select name="subunit_id" id="subunit_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Subunit (Optional)</option>
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
                                    </div>
                                    
                                    <button type="button" id="addPositionBtn" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                        Add Another Position
                                    </button>
                                </div>
                            </div>


                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-6 py-3 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                                Save Employee
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
            
            // Use pre-loaded cascading data
            const cascadingData = @json($cascadingData);
            
            // Load divisions when office is selected
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
            
            // Load units when division is selected (using pre-loaded data)
            divisionSelect.addEventListener('change', function() {
                const divisionId = this.value;
                console.log('Division changed to:', divisionId);
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                
                if (divisionId && cascadingData.units[divisionId]) {
                    console.log('Found units for division:', cascadingData.units[divisionId]);
                    cascadingData.units[divisionId].forEach(unit => {
                        unitSelect.innerHTML += '<option value="' + unit.id_unit + '">' + unit.unit_name + '</option>';
                    });
                } else {
                    console.log('No units found for division:', divisionId);
                    unitSelect.innerHTML = '<option value="">No units available</option>';
                }
            });
            
            // Load subunits when unit is selected (using pre-loaded data)
            unitSelect.addEventListener('change', function() {
                const unitId = this.value;
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                
                if (unitId && cascadingData.subunits[unitId]) {
                    cascadingData.subunits[unitId].forEach(subunit => {
                        subunitSelect.innerHTML += '<option value="' + subunit.id_subunit + '">' + subunit.subunit_name + '</option>';
                    });
                }
            });

            // Handle additional positions
            let positionCounter = 0;

            document.getElementById('addPositionBtn').addEventListener('click', function() {
                positionCounter++;
                const container = document.getElementById('additionalPositionsContainer');

                const positionDiv = document.createElement('div');
                positionDiv.className = 'additional-position-group border p-4 rounded-lg mb-4';
                positionDiv.dataset.positionIndex = positionCounter;
                
                // Build the HTML content properly
                let htmlContent = '<div class="flex justify-between items-center mb-2">';
                htmlContent += '<h4 class="text-md font-medium text-gray-800">Additional Position #' + positionCounter + '</h4>';
                htmlContent += '<button type="button" class="remove-position-btn bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded text-sm">Remove</button>';
                htmlContent += '</div>';

                htmlContent += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                htmlContent += '<div>';
                htmlContent += '<label class="block text-sm font-medium text-gray-700 mb-1">Position Name</label>';
                htmlContent += '<input type="text" name="additional_positions[' + positionCounter + '][position_name]" ';
                htmlContent += 'class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">';
                htmlContent += '</div>';

                htmlContent += '<div>';
                htmlContent += '<label class="block text-sm font-medium text-gray-700 mb-1">Class</label>';
                htmlContent += '<select name="additional_positions[' + positionCounter + '][class_id]" ';
                htmlContent += 'class="additional-class-select w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">';
                htmlContent += '<option value="">Select Class</option>';
                @foreach($classes as $class)
                htmlContent += '<option value="{{ $class->id }}">{{ addslashes($class->class_name) }}</option>';
                @endforeach
                htmlContent += '</select>';
                htmlContent += '</div>';

                htmlContent += '<div>';
                htmlContent += '<label class="block text-sm font-medium text-gray-700 mb-1">Office</label>';
                htmlContent += '<select name="additional_positions[' + positionCounter + '][office_id]" ';
                htmlContent += 'class="additional-office-select w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">';
                htmlContent += '<option value="">Select Office</option>';
                @foreach($offices as $office)
                htmlContent += '<option value="{{ $office->id }}">{{ addslashes($office->office_name) }}</option>';
                @endforeach
                htmlContent += '</select>';
                htmlContent += '</div>';

                htmlContent += '<div>';
                htmlContent += '<label class="block text-sm font-medium text-gray-700 mb-1">Division</label>';
                htmlContent += '<select name="additional_positions[' + positionCounter + '][division_id]" ';
                htmlContent += 'class="additional-division-select w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">';
                htmlContent += '<option value="">Select Division</option>';
                htmlContent += '</select>';
                htmlContent += '</div>';

                htmlContent += '<div>';
                htmlContent += '<label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>';
                htmlContent += '<select name="additional_positions[' + positionCounter + '][unit_id]" ';
                htmlContent += 'class="additional-unit-select w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">';
                htmlContent += '<option value="">Select Unit</option>';
                htmlContent += '</select>';
                htmlContent += '</div>';

                htmlContent += '<div>';
                htmlContent += '<label class="block text-sm font-medium text-gray-700 mb-1">Subunit</label>';
                htmlContent += '<select name="additional_positions[' + positionCounter + '][subunit_id]" ';
                htmlContent += 'class="additional-subunit-select w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">';
                htmlContent += '<option value="">Select Subunit</option>';
                htmlContent += '</select>';
                htmlContent += '</div>';

                htmlContent += '</div>';
                
                positionDiv.innerHTML = htmlContent;
                container.appendChild(positionDiv);

                // Add event listeners to the new dropdowns
                attachDropdownEventListeners(positionDiv, cascadingData);

                // Add event listener to the remove button
                positionDiv.querySelector('.remove-position-btn').addEventListener('click', function() {
                    positionDiv.remove();
                });
            });

            // Function to attach dropdown event listeners to a position group
            function attachDropdownEventListeners(group, data) {
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

                    if (officeId && data.divisions[officeId]) {
                        data.divisions[officeId].forEach(division => {
                            divisionSelect.innerHTML += '<option value="' + division.id_division + '">' + division.division_name + '</option>';
                        });
                    }
                });

                // Load units when division is selected for this group
                divisionSelect.addEventListener('change', function() {
                    const divisionId = this.value;
                    unitSelect.innerHTML = '<option value="">Loading units...</option>';
                    subunitSelect.innerHTML = '<option value="">Select Subunit</option>';

                    if (divisionId) {
                        fetch('{{ route('admin.employees.get-units-by-division') }}?division_id=' + divisionId, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            credentials: 'include'
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
                                unitSelect.innerHTML += '<option value="' + unit.id_unit + '">' + unit.unit_name + '</option>';
                            });
                        })
                        .catch(error => {
                            unitSelect.innerHTML = '<option value="">Error loading units</option>';
                        });
                    } else {
                        unitSelect.innerHTML = '<option value="">Select Unit</option>';
                    }
                });

                // Load subunits when unit is selected for this group
                unitSelect.addEventListener('change', function() {
                    const unitId = this.value;
                    subunitSelect.innerHTML = '<option value="">Loading subunits...</option>';

                    if (unitId) {
                        fetch('{{ route('admin.employees.get-subunits-by-unit') }}?unit_id=' + unitId, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            credentials: 'include'
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(subunits => {
                            subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                            subunits.forEach(subunit => {
                                subunitSelect.innerHTML += '<option value="' + subunit.id_subunit + '">' + subunit.subunit_name + '</option>';
                            });
                        })
                        .catch(error => {
                            subunitSelect.innerHTML = '<option value="">Error loading subunits</option>';
                        });
                    } else {
                        subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                    }
                });
            }
        });
    </script>
</x-app-layout>