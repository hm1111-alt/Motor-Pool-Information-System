<?php
// ULTIMATE EMPLOYEE CREATOR - Bypasses ALL Laravel complexity
// This creates employees directly in the database with no form submission issues

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get and sanitize input data
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $middle_initial = trim($_POST['middle_initial'] ?? '');
        $ext_name = trim($_POST['ext_name'] ?? '');
        $sex = trim($_POST['sex'] ?? 'M');
        $prefix = trim($_POST['prefix'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $contact_num = trim($_POST['contact_num'] ?? '');
        $position_name = trim($_POST['position_name'] ?? '');
        $role = trim($_POST['role'] ?? 'none');
        
        // Validate required fields
        $errors = [];
        if (empty($first_name)) $errors[] = 'First name is required';
        if (empty($last_name)) $errors[] = 'Last name is required';
        if (empty($email)) $errors[] = 'Email is required';
        if (empty($password)) $errors[] = 'Password is required';
        if (empty($position_name)) $errors[] = 'Position name is required';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters';
        
        if (empty($errors)) {
            // Start database transaction
            DB::beginTransaction();
            
            // 1. Insert into users table
            $userId = DB::table('users')->insertGetId([
                'name' => $first_name . ' ' . $last_name,
                'email' => $email,
                'contact_num' => $contact_num,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'employees',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // 2. Insert into employees table
            $employeeId = DB::table('employees')->insertGetId([
                'user_id' => $userId,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'middle_initial' => $middle_initial,
                'ext_name' => $ext_name,
                'full_name' => $first_name . ' ' . $last_name,
                'full_name2' => $last_name . ', ' . $first_name,
                'sex' => $sex,
                'prefix' => $prefix,
                'emp_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // 3. Insert into emp_positions table
            DB::table('emp_positions')->insert([
                'employee_id' => $employeeId,
                'position_name' => $position_name,
                'class_id' => null,
                'office_id' => null,
                'division_id' => null,
                'unit_id' => null,
                'subunit_id' => null,
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // 4. Insert into officers table (only if role is not 'none')
            if (!empty($role) && $role !== 'none') {
                DB::table('officers')->insert([
                    'employee_id' => $employeeId,
                    'unit_head' => $role === 'unit_head',
                    'division_head' => $role === 'division_head',
                    'vp' => $role === 'vp',
                    'president' => $role === 'president',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Commit transaction
            DB::commit();
            
            $success = true;
            $message = "✅ Employee created successfully!<br><br>"
                     . "<strong>Details:</strong><br>"
                     . "• Name: $first_name $last_name<br>"
                     . "• Email: $email<br>"
                     . "• Position: $position_name<br>"
                     . "• Role: " . ($role === 'none' ? 'Regular Employee' : ucfirst(str_replace('_', ' ', $role))) . "<br>"
                     . "• User ID: $userId<br>"
                     . "• Employee ID: $employeeId";
                     
        } else {
            $success = false;
            $message = "❌ Validation Errors:<br><br>" . implode('<br>', $errors);
        }
        
    } catch (Exception $e) {
        DB::rollBack();
        $success = false;
        $message = "❌ Database Error:<br><br>" . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ultimate Employee Creator</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1e6031 0%, #2a8a42 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { font-size: 2rem; margin-bottom: 10px; }
        .header p { opacity: 0.9; }
        .content { padding: 30px; }
        .form-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 20px; 
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #1e6031;
            box-shadow: 0 0 0 3px rgba(30, 96, 49, 0.1);
        }
        .required { color: #e74c3c; }
        .buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #1e6031;
            color: white;
        }
        .btn-primary:hover {
            background: #164f2a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 96, 49, 0.3);
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        .section h3 {
            color: #1e6031;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1e6031;
        }
        .notification {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        .notification.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .notification.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Ultimate Employee Creator</h1>
            <p>Bypasses all form submission issues - Creates employees directly in database</p>
        </div>
        
        <div class="content">
            <?php if (isset($success)): ?>
                <script>
                    Swal.fire({
                        icon: '<?= $success ? 'success' : 'error' ?>',
                        title: '<?= $success ? 'Success!' : 'Error!' ?>',
                        html: '<?= $message ?>',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e6031'
                    });
                </script>
            <?php endif; ?>
            
            <form method="post" id="employeeForm">
                <div class="section">
                    <h3>👤 Personal Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="first_name">First Name <span class="required">*</span></label>
                            <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($_POST['first_name'] ?? 'Direct') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="last_name">Last Name <span class="required">*</span></label>
                            <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($_POST['last_name'] ?? 'Creation') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="middle_initial">Middle Initial</label>
                            <input type="text" name="middle_initial" id="middle_initial" value="<?= htmlspecialchars($_POST['middle_initial'] ?? 'D') ?>" maxlength="10">
                        </div>
                        
                        <div class="form-group">
                            <label for="ext_name">Extension Name</label>
                            <input type="text" name="ext_name" id="ext_name" value="<?= htmlspecialchars($_POST['ext_name'] ?? '') ?>" maxlength="10">
                        </div>
                        
                        <div class="form-group">
                            <label for="sex">Sex <span class="required">*</span></label>
                            <select name="sex" id="sex" required>
                                <option value="M" <?= (($_POST['sex'] ?? '') === 'M') ? 'selected' : '' ?>>Male</option>
                                <option value="F" <?= (($_POST['sex'] ?? '') === 'F') ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="prefix">Prefix</label>
                            <input type="text" name="prefix" id="prefix" value="<?= htmlspecialchars($_POST['prefix'] ?? '') ?>" maxlength="10">
                        </div>
                    </div>
                </div>
                
                <div class="section">
                    <h3>📧 Contact & Security</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? ('direct.' . time() . '@test.com')) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password <span class="required">*</span></label>
                            <input type="password" name="password" id="password" value="password123" required minlength="8">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_num">Contact Number</label>
                            <input type="tel" name="contact_num" id="contact_num" value="<?= htmlspecialchars($_POST['contact_num'] ?? '555-777-8888') ?>">
                        </div>
                    </div>
                </div>
                
                <div class="section">
                    <h3>💼 Position Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="position_name">Position Name <span class="required">*</span></label>
                            <input type="text" name="position_name" id="position_name" value="<?= htmlspecialchars($_POST['position_name'] ?? 'Direct Creation Position') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Role Assignment</label>
                            <select name="role" id="role">
                                <option value="none" <?= (empty($_POST['role']) || $_POST['role'] === 'none') ? 'selected' : '' ?>>None (Regular Employee)</option>
                                <option value="unit_head" <?= (($_POST['role'] ?? '') === 'unit_head') ? 'selected' : '' ?>>Unit Head</option>
                                <option value="division_head" <?= (($_POST['role'] ?? '') === 'division_head') ? 'selected' : '' ?>>Division Head</option>
                                <option value="vp" <?= (($_POST['role'] ?? '') === 'vp') ? 'selected' : '' ?>>VP</option>
                                <option value="president" <?= (($_POST['role'] ?? '') === 'president') ? 'selected' : '' ?>>President</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="buttons">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                    <button type="submit" class="btn btn-primary">Create Employee</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function resetForm() {
            document.getElementById('employeeForm').reset();
            // Set default values
            document.getElementById('first_name').value = 'Direct';
            document.getElementById('last_name').value = 'Creation';
            document.getElementById('middle_initial').value = 'D';
            document.getElementById('email').value = 'direct.' + Date.now() + '@test.com';
            document.getElementById('password').value = 'password123';
            document.getElementById('contact_num').value = '555-777-8888';
            document.getElementById('position_name').value = 'Direct Creation Position';
            document.getElementById('role').value = 'none';
        }
        
        // Auto-focus first field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('first_name').focus();
        });
    </script>
</body>
</html>