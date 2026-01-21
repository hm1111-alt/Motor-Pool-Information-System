<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;

echo "=== Listing All Employees ===\n\n";

try {
    $employees = Employee::all();
    foreach ($employees as $employee) {
        echo "{$employee->id}: {$employee->first_name} {$employee->last_name} - Division ID: {$employee->division_id}\n";
    }
    
    echo "\n=== Checking Officer Records ===\n\n";
    
    foreach ($employees as $employee) {
        $officer = $employee->officer;
        if ($officer) {
            echo "Employee {$employee->id} ({$employee->first_name} {$employee->last_name}) has officer record:\n";
            echo "  unit_head: " . ($officer->unit_head ? 'true' : 'false') . "\n";
            echo "  division_head: " . ($officer->division_head ? 'true' : 'false') . "\n";
            echo "  vp: " . ($officer->vp ? 'true' : 'false') . "\n";
            echo "  president: " . ($officer->president ? 'true' : 'false') . "\n";
        } else {
            echo "Employee {$employee->id} ({$employee->first_name} {$employee->last_name}) has NO officer record\n";
        }
        echo "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}