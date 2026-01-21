<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Validator;
use App\Models\Employee;
use App\Models\User;

// Test creating an employee with a role
echo "Testing EmployeeController@store method with role assignment (single role)...\n";

$email = 'testrole' . time() . '@example.com';
$data = [
    'first_name' => 'Test',
    'last_name' => 'SingleRoleUser',
    'email' => $email,
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'position_name' => 'Test Position',
    'sex' => 'M',
    'role' => 'vp', // Assigning single role
    'emp_status' => 1, // Adding this for explicit status
];

try {
    // Create a request object
    $request = new Request($data);
    $request->setMethod('POST');

    // Validate the request data first to see if it passes validation
    $validator = Validator::make($data, [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'middle_initial' => 'nullable|string|max:10',
        'ext_name' => 'nullable|string|max:10',
        'sex' => 'required|string|in:M,F',
        'prefix' => 'nullable|string|max:10',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'position_name' => 'required|string|max:255',
        'office_id' => 'nullable|exists:offices,id',
        'division_id' => 'nullable|exists:divisions,id',
        'unit_id' => 'nullable|exists:units,id',
        'subunit_id' => 'nullable|exists:subunits,id',
        'class_id' => 'nullable|exists:class,id',
        'role' => 'nullable|in:unit_head,division_head,vp,president',
        'emp_status' => 'required|boolean',
    ]);

    if ($validator->fails()) {
        echo "Validation failed:\n";
        foreach ($validator->errors()->all() as $error) {
            echo "- $error\n";
        }
        exit(1);
    }

    echo "Validation passed.\n";

    // Create the controller instance and call store
    $controller = new EmployeeController();
    $result = $controller->store($request);

    echo "Store method executed successfully!\n";
    echo "Result type: " . get_class($result) . "\n";
    
    // Check if the user was created (email is in users table)
    $user = User::where('email', $email)->first();
    if ($user) {
        echo "User was created successfully with ID: {$user->id}\n";
        
        // Find the employee associated with this user
        $employee = Employee::where('user_id', $user->id)->with('officer')->first();
        if ($employee) {
            echo "Employee was created successfully with ID: {$employee->id}\n";
            echo "Employee name: {$employee->first_name} {$employee->last_name}\n";
            echo "Employee status (should be active): " . ($employee->emp_status ? 'Active' : 'Inactive') . "\n";
            
            // Check if officer record was created
            if ($employee->officer) {
                echo "Officer record created with ID: {$employee->officer->id}\n";
                echo "Roles - Unit Head: " . ($employee->officer->unit_head ? 'Yes' : 'No') . "\n";
                echo "Roles - Division Head: " . ($employee->officer->division_head ? 'Yes' : 'No') . "\n";
                echo "Roles - VP: " . ($employee->officer->vp ? 'Yes' : 'No') . " (Should be Yes)\n";
                echo "Roles - President: " . ($employee->officer->president ? 'Yes' : 'No') . "\n";
                
                // Test the virtual attributes
                echo "Virtual attributes test:\n";
                echo "  is_head: " . ($employee->is_head ? 'Yes' : 'No') . "\n";
                echo "  is_divisionhead: " . ($employee->is_divisionhead ? 'Yes' : 'No') . "\n";
                echo "  is_vp: " . ($employee->is_vp ? 'Yes' : 'No') . " (Should be Yes)\n";
                echo "  is_president: " . ($employee->is_president ? 'Yes' : 'No') . "\n";
                
                // Verify only one role is set
                $roleCount = 0;
                if ($employee->is_head) $roleCount++;
                if ($employee->is_divisionhead) $roleCount++;
                if ($employee->is_vp) $roleCount++;
                if ($employee->is_president) $roleCount++;
                
                echo "Total roles assigned: $roleCount (should be 1)\n";
            } else {
                echo "No officer record created\n";
            }
        } else {
            echo "No employee found for this user\n";
        }
    } else {
        echo "No user found in database\n";
    }

    // Clean up test data
    if ($user) {
        if ($employee && $employee->officer) {
            $employee->officer->delete();
        }
        if ($employee) {
            $employee->delete();
        }
        $user->delete();
        echo "Test data cleaned up successfully.\n";
    }

} catch (Exception $e) {
    echo "Error occurred: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}