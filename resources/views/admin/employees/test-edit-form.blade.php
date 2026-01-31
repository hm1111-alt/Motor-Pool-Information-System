<!DOCTYPE html>
<html>
<head>
    <title>Test Employee Edit</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Employee Edit Form</h1>
    
    <form id="testForm" action="/admin/employees/74" method="POST">
        @csrf
        @method('PUT')
        
        <div>
            <label>First Name:</label>
            <input type="text" name="first_name" value="Evaristo" required>
        </div>
        
        <div>
            <label>Last Name:</label>
            <input type="text" name="last_name" value="Abella" required>
        </div>
        
        <div>
            <label>Middle Initial:</label>
            <input type="text" name="middle_initial" value="">
        </div>
        
        <div>
            <label>Extension Name:</label>
            <input type="text" name="ext_name" value="">
        </div>
        
        <div>
            <label>Sex:</label>
            <select name="sex" required>
                <option value="M" selected>Male</option>
                <option value="F">Female</option>
            </select>
        </div>
        
        <div>
            <label>Prefix:</label>
            <input type="text" name="prefix" value="Dr.">
        </div>
        
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="evaristo.abella@clsu.edu.ph" required>
        </div>
        
        <div>
            <label>Password:</label>
            <input type="password" name="password">
        </div>
        
        <div>
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation">
        </div>
        
        <div>
            <label>Contact Number:</label>
            <input type="tel" name="contact_num" value="">
        </div>
        
        <div>
            <label>Position Name:</label>
            <input type="text" name="position_name" value="University President" required>
        </div>
        
        <div>
            <label>Position Role:</label>
            <select name="position_role">
                <option value="none">Regular Employee</option>
                <option value="unit_head">Unit Head</option>
                <option value="division_head">Division Head</option>
                <option value="vp">VP</option>
                <option value="president" selected>President</option>
            </select>
        </div>
        
        <div>
            <label>Class:</label>
            <select name="class_id">
                <option value="">Select Class</option>
                <option value="1">Class 1</option>
                <option value="2">Class 2</option>
            </select>
        </div>
        
        <div>
            <label>Office:</label>
            <select name="office_id">
                <option value="">Select Office</option>
                <option value="1">Office 1</option>
                <option value="2">Office 2</option>
            </select>
        </div>
        
        <div>
            <label>Division:</label>
            <select name="division_id">
                <option value="">Select Division</option>
                <option value="1">Division 1</option>
                <option value="2">Division 2</option>
            </select>
        </div>
        
        <div>
            <label>Unit:</label>
            <select name="unit_id">
                <option value="">Select Unit</option>
                <option value="1">Unit 1</option>
                <option value="2">Unit 2</option>
            </select>
        </div>
        
        <div>
            <label>Subunit:</label>
            <select name="subunit_id">
                <option value="">Select Subunit</option>
                <option value="1">Subunit 1</option>
                <option value="2">Subunit 2</option>
            </select>
        </div>
        
        <div>
            <button type="submit">Update Employee</button>
        </div>
    </form>
    
    <div id="result"></div>
    
    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('result');
            
            fetch('/admin/employees/74', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(response => response.text())
            .then(data => {
                resultDiv.innerHTML = '<pre>' + data + '</pre>';
            })
            .catch(error => {
                resultDiv.innerHTML = '<p style="color: red;">Error: ' + error + '</p>';
            });
        });
    </script>
</body>
</html>