<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Employee;

echo "=== Checking User-Employee Relationships ===\n\n";

try {
    // Get all users
    $users = User::all();
    echo "Total users: " . $users->count() . "\n\n";
    
    foreach ($users as $user) {
        echo "User: {$user->name} (ID: {$user->id}, Email: {$user->email})\n";
        echo "  Role: {$user->role}\n";
        
        // Try to get the employee relationship
        $employee = $user->employee;
        if ($employee) {
            echo "  -> Employee: {$employee->first_name} {$employee->last_name} (ID: {$employee->id})\n";
            echo "  -> Employee is_head: " . ($employee->is_head ? 'true' : 'false') . "\n";
            echo "  -> Employee is_divisionhead: " . ($employee->is_divisionhead ? 'true' : 'false') . "\n";
            echo "  -> Employee is_vp: " . ($employee->is_vp ? 'true' : 'false') . "\n";
            echo "  -> Employee is_president: " . ($employee->is_president ? 'true' : 'false') . "\n";
        } else {
            echo "  -> NO EMPLOYEE RELATIONSHIP FOUND\n";
            
            // Try to find if there's an employee that might match by email or other criteria
            $matchingEmployee = Employee::where('email', $user->email)->first();
            if ($matchingEmployee) {
                echo "  -> Found matching employee by email: {$matchingEmployee->first_name} {$matchingEmployee->last_name}\n";
            } else {
                echo "  -> No matching employee found by email\n";
            }
        }
        echo "\n";
    }
    
    // Also check employees that might not have user relationships
    echo "=== Checking Employees without User Relations ===\n\n";
    
    $employees = Employee::all();
    echo "Total employees: " . $employees->count() . "\n";
    
    $noUserCount = 0;
    foreach ($employees as $employee) {
        $userCheck = User::find($employee->user_id);
        if (!$userCheck) {
            echo "Employee: {$employee->first_name} {$employee->last_name} (ID: {$employee->id}) has user_id: {$employee->user_id} but no matching user found\n";
            $noUserCount++;
        }
    }
    
    echo "\nEmployees without matching users: {$noUserCount}\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}