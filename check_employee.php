<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$employee = App\Models\Employee::with(['user', 'positions'])->first();
if($employee) {
    echo "Employee ID: " . $employee->id . "\n";
    echo "Name: " . $employee->first_name . " " . $employee->last_name . "\n";
    echo "Positions: " . $employee->positions->count() . "\n";
    
    $primary = $employee->positions->where('is_primary', true)->first();
    if($primary) {
        echo "Primary Position: " . $primary->position_name . "\n";
        echo "Office ID: " . $primary->office_id . "\n";
        echo "Division ID: " . $primary->division_id . "\n";
        echo "Unit ID: " . $primary->unit_id . "\n";
        echo "Subunit ID: " . $primary->subunit_id . "\n";
        echo "Class ID: " . $primary->class_id . "\n";
        echo "Is Unit Head: " . ($primary->is_unit_head ? 'Yes' : 'No') . "\n";
        echo "Is Division Head: " . ($primary->is_division_head ? 'Yes' : 'No') . "\n";
        echo "Is VP: " . ($primary->is_vp ? 'Yes' : 'No') . "\n";
        echo "Is President: " . ($primary->is_president ? 'Yes' : 'No') . "\n";
    } else {
        echo "No primary position found\n";
    }
    
    // Check user data
    if($employee->user) {
        echo "User Email: " . $employee->user->email . "\n";
    } else {
        echo "No user record found\n";
    }
} else {
    echo "No employees found\n";
}
?>