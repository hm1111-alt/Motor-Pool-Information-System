<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;

// Check employee model attributes
try {
    $employee = Employee::first();
    echo "Employee attributes:\n";
    print_r(array_keys($employee->toArray()));
    
    echo "\nChecking if is_vp exists:\n";
    echo "is_vp: " . ($employee->is_vp ? 'Yes' : 'No') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}