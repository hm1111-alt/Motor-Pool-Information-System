<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Finding All Employees in Division 18 (College of Engineering) ===\n\n";

$employeesInDivision18 = \App\Models\Employee::whereHas('positions', function($query) {
    $query->where('division_id', 18);
})->with('positions')->get();

if ($employeesInDivision18->isEmpty()) {
    echo "No employees found in Division 18\n";
    exit;
}

echo "Employees in Division 18:\n";
foreach ($employeesInDivision18 as $employee) {
    echo "- {$employee->first_name} {$employee->last_name} (ID: {$employee->id})\n";
    echo "  is_divisionhead: " . ($employee->is_divisionhead ? 'Yes' : 'No') . "\n";
    
    foreach ($employee->positions as $position) {
        if ($position->division_id == 18) {
            echo "  Position: {$position->position_name}\n";
            echo "  Is Primary: " . ($position->is_primary ? 'Yes' : 'No') . "\n";
            echo "  Is Division Head: " . ($position->is_division_head ? 'Yes' : 'No') . "\n";
            echo "  Division ID: {$position->division_id}\n";
        }
    }
    
    // Check if there's a user account
    $user = \App\Models\User::where('employee_id', $employee->id)->first();
    if ($user) {
        echo "  User Email: {$user->email}\n";
    } else {
        echo "  ⚠️ No user account\n";
    }
    
    echo "\n";
}

// Also check specifically for division heads in Division 18
echo "\n=== Division Heads in Division 18 ===\n";
$divisionHeadsInDivision18 = \App\Models\Employee::whereHas('positions', function($query) {
    $query->where('division_id', 18)
          ->where('is_division_head', true)
          ->where('is_primary', true);
})->with('positions')->get();

if ($divisionHeadsInDivision18->isEmpty()) {
    echo "No division heads found in Division 18\n";
} else {
    foreach ($divisionHeadsInDivision18 as $dh) {
        echo "- {$dh->first_name} {$dh->last_name} (ID: {$dh->id})\n";
        echo "  is_divisionhead: " . ($dh->is_divisionhead ? 'Yes' : 'No') . "\n";
        
        foreach ($dh->positions as $position) {
            if ($position->is_division_head) {
                echo "  Division Head Position: {$position->position_name}\n";
                echo "  Division ID: {$position->division_id}\n";
            }
        }
    }
}

?>