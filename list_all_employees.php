<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== All Employees in Database ===\n\n";

$employees = \App\Models\Employee::all();

foreach ($employees as $employee) {
    echo $employee->id . ': ' . $employee->first_name . ' ' . $employee->last_name . ' (user_id: ' . ($employee->user_id ?? 'null') . ')' . "\n";
}

echo "\n=== Employees in Division 18 (College of Engineering) ===\n\n";

$employeesInDivision18 = \App\Models\Employee::whereHas('positions', function($query) {
    $query->where('division_id', 18);
})->with('positions')->get();

foreach ($employeesInDivision18 as $employee) {
    echo $employee->id . ': ' . $employee->first_name . ' ' . $employee->last_name . ' (user_id: ' . ($employee->user_id ?? 'null') . ')' . "\n";
    foreach ($employee->positions as $position) {
        if ($position->division_id == 18) {
            echo "  - Position: {$position->position_name} | Division Head: " . ($position->is_division_head ? 'Yes' : 'No') . " | Primary: " . ($position->is_primary ? 'Yes' : 'No') . "\n";
        }
    }
    echo "\n";
}

?>