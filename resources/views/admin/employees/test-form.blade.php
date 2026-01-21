<!DOCTYPE html>
<html>
<head>
    <title>Employee Creation Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #218838; }
        .result { margin-top: 20px; padding: 15px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Employee Creation Test Form</h1>
        <p>This is a simplified test form to debug employee creation issues.</p>
        
        <form id="testForm" method="POST" action="{{ route('admin.employees.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="first_name">First Name *</label>
                <input type="text" name="first_name" id="first_name" value="Test" required>
            </div>
            
            <div class="form-group">
                <label for="last_name">Last Name *</label>
                <input type="text" name="last_name" id="last_name" value="User" required>
            </div>
            
            <div class="form-group">
                <label for="middle_initial">Middle Initial</label>
                <input type="text" name="middle_initial" id="middle_initial" value="T" maxlength="10">
            </div>
            
            <div class="form-group">
                <label for="ext_name">Extension Name</label>
                <input type="text" name="ext_name" id="ext_name" value="" maxlength="10">
            </div>
            
            <div class="form-group">
                <label for="sex">Sex *</label>
                <select name="sex" id="sex" required>
                    <option value="M">Male</option>
                    <option value="F" selected>Female</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="prefix">Prefix</label>
                <input type="text" name="prefix" id="prefix" value="Ms." maxlength="10">
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" name="email" id="email" value="test.{{ time() }}@example.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" name="password" id="password" value="password123" required>
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirm Password *</label>
                <input type="password" name="password_confirmation" id="password_confirmation" value="password123" required>
            </div>
            
            <div class="form-group">
                <label for="contact_num">Contact Number</label>
                <input type="tel" name="contact_num" id="contact_num" value="555-123-4567">
            </div>
            
            <div class="form-group">
                <label for="position_name">Position Name *</label>
                <input type="text" name="position_name" id="position_name" value="Test Position" required>
            </div>
            
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role">
                    <option value="none" selected>None (Regular Employee)</option>
                    <option value="unit_head">Unit Head</option>
                    <option value="division_head">Division Head</option>
                    <option value="vp">VP</option>
                    <option value="president">President</option>
                </select>
            </div>
            
            <button type="submit">Create Employee</button>
        </form>
        
        <div id="result"></div>
    </div>

    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('result');
            
            // Show what we're sending
            console.log('Form data being sent:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            // Send the request
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    resultDiv.innerHTML = '<div class="result success">✓ Success: ' + data.message + '</div>';
                } else {
                    resultDiv.innerHTML = '<div class="result error">✗ Error: ' + (data.message || 'Unknown error') + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultDiv.innerHTML = '<div class="result error">✗ Network Error: ' + error.message + '</div>';
            });
        });
    </script>
</body>
</html>