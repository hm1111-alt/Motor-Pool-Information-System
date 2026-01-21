<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Employee;

echo "=== Checking Single User-Employee Relationship ===\n\n";

try {
    // Get the first user
    $user = User::first();
    if ($user) {
        echo "User: {$user->name} (ID: {$user->id}, Email: {$user->email})\n";
        echo "  Role: {$user->role}\n";
        echo "  User ID: {$user->id}\n";
        
        // Get employee relationship
        $employee = $user->employee;
        if ($employee) {
            echo "  -> Has employee relation: {$employee->first_name} {$employee->last_name} (ID: {$employee->id})\n";
            echo "  -> Employee user_id: {$employee->user_id}\n";
            echo "  -> Match: " . ($employee->user_id == $user->id ? 'YES' : 'NO') . "\n";
        } else {
            echo "  -> NO EMPLOYEE RELATIONSHIP FOUND\n";
        }
        
        // Check if we can access the employee directly by ID
        $empById = Employee::find($user->id);
        if ($empById) {
            echo "  -> Found employee by same ID: {$empById->first_name} {$empById->last_name}\n";
        } else {
            echo "  -> No employee with same ID\n";
        }
    } else {
        echo "No users found in the database.\n";
    }
    
    echo "\n---\n\n";
    
    // Check the first employee
    $employee = Employee::first();
    if ($employee) {
        echo "Employee: {$employee->first_name} {$employee->last_name} (ID: {$employee->id})\n";
        echo "  Employee user_id: {$employee->user_id}\n";
        
        // Get user relationship
        $user = $employee->user;
        if ($user) {
            echo "  -> Has user relation: {$user->name} (ID: {$user->id})\n";
            echo "  -> User ID: {$user->id}\n";
            echo "  -> Match: " . ($employee->user_id == $user->id ? 'YES' : 'NO') . "\n";
        } else {
            echo "  -> NO USER RELATIONSHIP FOUND\n";
        }
        
        // Check if we can access the user directly by ID
        $userById = User::find($employee->user_id);
        if ($userById) {
            echo "  -> Found user by employee.user_id: {$userById->name} (ID: {$userById->id})\n";
        } else {
            echo "  -> No user with employee.user_id\n";
        }
    } else {
        echo "No employees found in the database.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}