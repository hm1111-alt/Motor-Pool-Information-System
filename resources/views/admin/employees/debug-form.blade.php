@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6">Debug Employee Creation Form</h2>
                
                <!-- Debug Console Output -->
                <div id="debugConsole" class="mb-6 p-4 bg-gray-100 rounded-lg hidden">
                    <h3 class="font-bold mb-2">Debug Console:</h3>
                    <pre id="debugOutput" class="text-sm whitespace-pre-wrap"></pre>
                </div>
                
                <form method="post" action="{{ route('admin.employees.store') }}" id="debugEmployeeForm">
                    @csrf
                    
                    <!-- Personal Information Section -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                                <input type="text" name="first_name" id="first_name" value="Debug" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                            </div>
                            
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                                <input type="text" name="last_name" id="last_name" value="User" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                            </div>
                            
                            <div>
                                <label for="middle_initial" class="block text-sm font-medium text-gray-700 mb-1">Middle Initial</label>
                                <input type="text" name="middle_initial" id="middle_initial" value="D" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" maxlength="10">
                            </div>
                            
                            <div>
                                <label for="ext_name" class="block text-sm font-medium text-gray-700 mb-1">Extension Name</label>
                                <input type="text" name="ext_name" id="ext_name" value="" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" maxlength="10">
                            </div>
                            
                            <div>
                                <label for="sex" class="block text-sm font-medium text-gray-700 mb-1">Sex *</label>
                                <select name="sex" id="sex" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                                    <option value="M">Male</option>
                                    <option value="F" selected>Female</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="prefix" class="block text-sm font-medium text-gray-700 mb-1">Prefix</label>
                                <input type="text" name="prefix" id="prefix" value="Ms." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" maxlength="10">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" name="email" id="email" value="debug.{{ time() }}@test.com" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
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
                                <input type="tel" name="contact_num" id="contact_num" value="555-999-8888" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Position Information -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Position Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="position_name" class="block text-sm font-medium text-gray-700 mb-1">Position Name *</label>
                                <input type="text" name="position_name" id="position_name" value="Debug Position" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required>
                            </div>
                            
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select name="role" id="role" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <option value="none" selected>None (Regular Employee)</option>
                                    <option value="unit_head">Unit Head</option>
                                    <option value="division_head">Division Head</option>
                                    <option value="vp">VP</option>
                                    <option value="president">President</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex items-center justify-end mt-6 space-x-4">
                        <button type="button" id="showDebugBtn" class="bg-gray-500 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition duration-300">
                            Show Debug Info
                        </button>
                        <button type="submit" id="submitBtn" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-6 py-3 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                            Debug Create Employee
                        </button>
                    </div>
                </form>
                
                <!-- Result Display -->
                <div id="resultArea" class="mt-6 hidden">
                    <h3 class="text-lg font-medium mb-2">Submission Result:</h3>
                    <div id="resultContent" class="p-4 rounded-lg"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('debugEmployeeForm');
    const debugConsole = document.getElementById('debugConsole');
    const debugOutput = document.getElementById('debugOutput');
    const resultArea = document.getElementById('resultArea');
    const resultContent = document.getElementById('resultContent');
    const showDebugBtn = document.getElementById('showDebugBtn');
    
    // Show debug information
    showDebugBtn.addEventListener('click', function() {
        debugConsole.classList.toggle('hidden');
        
        // Log form details
        let debugInfo = "=== FORM DEBUG INFO ===\n";
        debugInfo += `Form Action: ${form.action}\n`;
        debugInfo += `Form Method: ${form.method}\n`;
        debugInfo += `CSRF Token Present: ${!!document.querySelector('input[name="_token"]')}\n`;
        debugInfo += `CSRF Token Value: ${document.querySelector('input[name="_token"]')?.value?.substring(0, 20)}...\n`;
        
        // Log all form data
        debugInfo += "\n=== FORM DATA ===\n";
        const formData = new FormData(form);
        for (let [key, value] of formData.entries()) {
            debugInfo += `${key}: ${value}\n`;
        }
        
        debugOutput.textContent = debugInfo;
    });
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        console.log('=== FORM SUBMISSION STARTED ===');
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Submitting...';
        submitBtn.disabled = true;
        
        const formData = new FormData(this);
        
        // Log what we're sending
        console.log('Sending form data:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        
        // Send AJAX request
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response received:', response.status, response.statusText);
            
            // Check if response is ok
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Success response data:', data);
            
            // Display success
            resultArea.classList.remove('hidden');
            resultContent.innerHTML = `
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <strong>✓ SUCCESS!</strong><br>
                    Message: ${data.message || 'Employee created successfully'}<br>
                    Data: ${JSON.stringify(data.data || {}, null, 2)}
                </div>
            `;
            
            // Try to show SweetAlert if available
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Employee created successfully',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error occurred:', error);
            
            // Display error
            resultArea.classList.remove('hidden');
            resultContent.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>✗ ERROR!</strong><br>
                    Message: ${error.message}<br>
                    Please check the browser console for more details.
                </div>
            `;
            
            // Try to show error SweetAlert
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message,
                    confirmButtonText: 'OK'
                });
            }
        })
        .finally(() => {
            // Restore button
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
});
</script>
@endsection