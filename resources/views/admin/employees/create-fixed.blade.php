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
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Please fix the following errors:</strong>
                            <ul class="list-disc pl-5 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('admin.employees.store') }}" id="fixedEmployeeForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Personal Information Section -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', 'Fixed') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                    </div>

                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', 'Test') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                    </div>

                                    <div>
                                        <label for="middle_initial" class="block text-sm font-medium text-gray-700 mb-1">Middle Initial</label>
                                        <input type="text" name="middle_initial" id="middle_initial" value="{{ old('middle_initial', 'T') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" maxlength="10">
                                    </div>

                                    <div>
                                        <label for="ext_name" class="block text-sm font-medium text-gray-700 mb-1">Extension Name</label>
                                        <input type="text" name="ext_name" id="ext_name" value="{{ old('ext_name') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" maxlength="10">
                                    </div>

                                    <div>
                                        <label for="sex" class="block text-sm font-medium text-gray-700 mb-1">Sex *</label>
                                        <select name="sex" id="sex" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                            <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Male</option>
                                            <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="prefix" class="block text-sm font-medium text-gray-700 mb-1">Prefix</label>
                                        <input type="text" name="prefix" id="prefix" value="{{ old('prefix') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" maxlength="10">
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', 'fixed.test.' . time() . '@example.com') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                                        <input type="password" name="password" id="password" value="password123" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" value="password123" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                    </div>

                                    <div>
                                        <label for="contact_num" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                                        <input type="tel" name="contact_num" id="contact_num" value="{{ old('contact_num', '555-111-2222') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    </div>
                                </div>
                            </div>

                            <!-- Position Information Section -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Primary Position Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="position_name" class="block text-sm font-medium text-gray-700 mb-1">Position Name *</label>
                                        <input type="text" name="position_name" id="position_name" value="{{ old('position_name', 'Fixed Test Position') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                    </div>

                                    <div>
                                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Class (Optional)</label>
                                        <select name="class_id" id="class_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Class (Optional)</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="office_id" class="block text-sm font-medium text-gray-700 mb-1">Office (Optional)</label>
                                        <select name="office_id" id="office_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Office (Optional)</option>
                                            @foreach($offices as $office)
                                                <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">Division (Optional)</label>
                                        <select name="division_id" id="division_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Division (Optional)</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>{{ $division->division_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">Unit (Optional)</label>
                                        <select name="unit_id" id="unit_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Unit (Optional)</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="subunit_id" class="block text-sm font-medium text-gray-700 mb-1">Subunit (Optional)</label>
                                        <select name="subunit_id" id="subunit_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                            <option value="">Select Subunit (Optional)</option>
                                            @foreach($subunits as $subunit)
                                                <option value="{{ $subunit->id }}" {{ old('subunit_id') == $subunit->id ? 'selected' : '' }}>{{ $subunit->subunit_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Role Assignment Section -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Role Assignment</h3>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Role:</label>
                                    @foreach($roles as $role)
                                        <div class="flex items-center">
                                            <input type="radio" name="role" id="role_{{ $role->id }}" value="{{ $role->name }}" class="mr-2" {{ (old('role') == $role->name || (empty(old('role')) && $role->name == 'none')) ? 'checked' : '' }}>
                                            <label for="role_{{ $role->id }}" class="text-sm text-gray-700">{{ $role->name == 'none' ? 'None (Regular Employee)' : ucfirst(str_replace('_', ' ', $role->name)) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <button type="button" id="testConnectionBtn" class="bg-gray-500 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition duration-300">
                                Test Connection
                            </button>
                            <button type="submit" id="submitBtn" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-6 py-3 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                                Save Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple JavaScript for debugging -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('fixedEmployeeForm');
        const submitBtn = document.getElementById('submitBtn');
        const testBtn = document.getElementById('testConnectionBtn');
        
        // Test connection button
        testBtn.addEventListener('click', function() {
            fetch('{{ route('admin.employees.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({test: true})
            })
            .then(response => response.json())
            .then(data => {
                alert('Connection test successful! Server responded.');
            })
            .catch(error => {
                alert('Connection test failed: ' + error.message);
                console.error('Connection test error:', error);
            });
        });
        
        // Simple form submission with error handling
        form.addEventListener('submit', function(e) {
            // Basic validation
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const positionName = document.getElementById('position_name').value.trim();
            
            if (!firstName || !lastName || !email || !password || !positionName) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password and confirmation do not match.');
                return;
            }
            
            // Show loading state
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Saving...';
            submitBtn.disabled = true;
            
            // Allow form to submit naturally
            // The backend will handle everything else
            console.log('Form submitted successfully');
        });
    });
    </script>
</x-app-layout>