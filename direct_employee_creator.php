<?php
// Guaranteed employee creation script - bypasses all frontend issues
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Employee;
use App\Models\EmpPosition;
use App\Models\Officer;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Employee Creation Tool</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px; }
        button:hover { background-color: #218838; }
        .result { margin-top: 20px; padding: 15px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    </style>
</head>
<body>
<div class='container'>
<h1>🔧 Employee Creation Tool</h1>
<p>This tool bypasses all frontend issues and creates employees directly via PHP.</p>";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'middle_initial' => $_POST['middle_initial'] ?? '',
            'ext_name' => $_POST['ext_name'] ?? '',
            'sex' => $_POST['sex'] ?? 'M',
            'prefix' => $_POST['prefix'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'contact_num' => $_POST['contact_num'] ?? '',
            'position_name' => $_POST['position_name'] ?? '',
            'role' => $_POST['role'] ?? 'none'
        ];
        
        // Validate required fields
        $errors = [];
        if (empty($data['first_name'])) $errors[] = 'First name is required';
        if (empty($data['last_name'])) $errors[] = 'Last name is required';
        if (empty($data['email'])) $errors[] = 'Email is required';
        if (empty($data['password'])) $errors[] = 'Password is required';
        if (empty($data['position_name'])) $errors[] = 'Position name is required';
        
        if (empty($errors)) {
            // Create the employee records
            DB::beginTransaction();
            
            // 1. Create User
            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'contact_num' => $data['contact_num'],
                'role' => User::ROLE_EMPLOYEE,
            ]);
            
            // 2. Create Employee
            $employee = Employee::create([
                'user_id' => $user->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_initial' => $data['middle_initial'],
                'ext_name' => $data['ext_name'],
                'full_name' => $data['first_name'] . ' ' . $data['last_name'],
                'full_name2' => $data['last_name'] . ', ' . $data['first_name'],
                'sex' => $data['sex'],
                'prefix' => $data['prefix'],
                'emp_status' => 1,
            ]);
            
            // 3. Create Position
            EmpPosition::create([
                'employee_id' => $employee->id,
                'position_name' => $data['position_name'],
                'office_id' => null,
                'division_id' => null,
                'unit_id' => null,
                'subunit_id' => null,
                'class_id' => null,
                'is_primary' => true,
            ]);
            
            // 4. Create Officer record if needed
            if (!empty($data['role']) && $data['role'] !== 'none') {
                Officer::create([
                    'employee_id' => $employee->id,
                    'unit_head' => $data['role'] === 'unit_head',
                    'division_head' => $data['role'] === 'division_head',
                    'vp' => $data['role'] === 'vp',
                    'president' => $data['role'] === 'president',
                ]);
            }
            
            DB::commit();
            
            echo "<div class='result success'>
                <h3>✅ Success!</h3>
                <p><strong>Employee created successfully!</strong></p>
                <ul>
                    <li>User ID: {$user->id}</li>
                    <li>Employee ID: {$employee->id}</li>
                    <li>Name: {$employee->first_name} {$employee->last_name}</li>
                    <li>Email: {$user->email}</li>
                    <li>Position: {$data['position_name']}</li>
                    <li>Role: " . ($data['role'] === 'none' ? 'Regular Employee' : ucfirst(str_replace('_', ' ', $data['role']))) . "</li>
                </ul>
            </div>";
            
        } else {
            echo "<div class='result error'><h3>❌ Validation Errors:</h3><ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul></div>";
        }
        
    } catch (Exception $e) {
        DB::rollBack();
        echo "<div class='result error'>
            <h3>❌ Database Error:</h3>
            <p>" . htmlspecialchars($e->getMessage()) . "</p>
            <details><summary>Technical Details</summary><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre></details>
        </div>";
    }
}

echo "
<form method='post'>
    <h2>Create New Employee</h2>
    
    <div class='form-group'>
        <label for='first_name'>First Name *</label>
        <input type='text' name='first_name' id='first_name' value='" . htmlspecialchars($_POST['first_name'] ?? 'Direct') . "' required>
    </div>
    
    <div class='form-group'>
        <label for='last_name'>Last Name *</label>
        <input type='text' name='last_name' id='last_name' value='" . htmlspecialchars($_POST['last_name'] ?? 'Creation') . "' required>
    </div>
    
    <div class='form-group'>
        <label for='middle_initial'>Middle Initial</label>
        <input type='text' name='middle_initial' id='middle_initial' value='" . htmlspecialchars($_POST['middle_initial'] ?? 'D') . "' maxlength='10'>
    </div>
    
    <div class='form-group'>
        <label for='ext_name'>Extension Name</label>
        <input type='text' name='ext_name' id='ext_name' value='" . htmlspecialchars($_POST['ext_name'] ?? '') . "' maxlength='10'>
    </div>
    
    <div class='form-group'>
        <label for='sex'>Sex *</label>
        <select name='sex' id='sex' required>
            <option value='M'" . (($_POST['sex'] ?? '') === 'M' ? ' selected' : '') . ">Male</option>
            <option value='F'" . (($_POST['sex'] ?? '') === 'F' ? ' selected' : '') . ">Female</option>
        </select>
    </div>
    
    <div class='form-group'>
        <label for='prefix'>Prefix</label>
        <input type='text' name='prefix' id='prefix' value='" . htmlspecialchars($_POST['prefix'] ?? '') . "' maxlength='10'>
    </div>
    
    <div class='form-group'>
        <label for='email'>Email *</label>
        <input type='email' name='email' id='email' value='" . htmlspecialchars($_POST['email'] ?? ('direct.' . time() . '@test.com')) . "' required>
    </div>
    
    <div class='form-group'>
        <label for='password'>Password *</label>
        <input type='password' name='password' id='password' value='password123' required>
    </div>
    
    <div class='form-group'>
        <label for='contact_num'>Contact Number</label>
        <input type='tel' name='contact_num' id='contact_num' value='" . htmlspecialchars($_POST['contact_num'] ?? '555-000-1111') . "'>
    </div>
    
    <div class='form-group'>
        <label for='position_name'>Position Name *</label>
        <input type='text' name='position_name' id='position_name' value='" . htmlspecialchars($_POST['position_name'] ?? 'Direct Creation Position') . "' required>
    </div>
    
    <div class='form-group'>
        <label for='role'>Role</label>
        <select name='role' id='role'>
            <option value='none'" . (empty($_POST['role']) || $_POST['role'] === 'none' ? ' selected' : '') . ">None (Regular Employee)</option>
            <option value='unit_head'" . (($_POST['role'] ?? '') === 'unit_head' ? ' selected' : '') . ">Unit Head</option>
            <option value='division_head'" . (($_POST['role'] ?? '') === 'division_head' ? ' selected' : '') . ">Division Head</option>
            <option value='vp'" . (($_POST['role'] ?? '') === 'vp' ? ' selected' : '') . ">VP</option>
            <option value='president'" . (($_POST['role'] ?? '') === 'president' ? ' selected' : '') . ">President</option>
        </select>
    </div>
    
    <button type='submit'>Create Employee</button>
    <button type='reset'>Reset Form</button>
</form>

<div class='result info'>
    <h3>ℹ️ Why This Works</h3>
    <p>This tool bypasses all frontend JavaScript, form validation, and AJAX issues that might be preventing your regular form from working. It uses direct PHP database calls just like your backend tests.</p>
    <p>If this works but your regular form doesn't, the issue is definitely in the frontend code, not the database or backend logic.</p>
</div>

</div>
</body>
</html>";