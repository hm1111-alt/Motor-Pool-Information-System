<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Assign Division Head for College of Engineering (Division ID 18) ===\n\n";

// Find the College of Engineering division
$collegeOfEngineering = \App\Models\Division::find(18);
if (!$collegeOfEngineering) {
    echo "Division ID 18 not found\n";
    exit;
}

echo "Division: {$collegeOfEngineering->division_name}\n";
echo "ID: {$collegeOfEngineering->id}\n\n";

// Find employees who could potentially be division heads for this division
$potentialEmployees = \App\Models\Employee::whereHas('positions', function($query) use ($collegeOfEngineering) {
    $query->where('division_id', $collegeOfEngineering->id)
          ->where('is_primary', true);
})->with(['positions' => function($query) use ($collegeOfEngineering) {
    $query->where('division_id', $collegeOfEngineering->id);
}])->get();

echo "Employees in College of Engineering:\n";
foreach ($potentialEmployees as $employee) {
    $position = $employee->positions->first(); // Since we filtered for the specific division
    echo "- {$employee->first_name} {$employee->last_name} (ID: {$employee->id})\n";
    echo "  Position: {$position->position_name}\n";
    echo "  Is Division Head: " . ($position->is_division_head ? 'Yes' : 'No') . "\n";
    echo "  Is Primary: " . ($position->is_primary ? 'Yes' : 'No') . "\n";
    echo "\n";
}

if ($potentialEmployees->count() == 0) {
    echo "No employees found in Division ID 18. Need to assign someone to this division first.\n";
    exit;
}

// Example: Let's assign the first employee found as the division head
// In practice, you would select the appropriate person
$employeeToMakeDivisionHead = $potentialEmployees->first();
$positionToModify = $employeeToMakeDivisionHead->positions->first();

echo "Attempting to assign {$employeeToMakeDivisionHead->first_name} {$employeeToMakeDivisionHead->last_name} as division head...\n";

try {
    // Update the position to be a division head
    $positionToModify->update([
        'is_division_head' => true
    ]);
    
    // Also update the employee's is_divisionhead attribute for backward compatibility
    $employeeToMakeDivisionHead->update([
        'is_divisionhead' => true
    ]);

    echo "Successfully assigned {$employeeToMakeDivisionHead->first_name} {$employeeToMakeDivisionHead->last_name} as division head for College of Engineering!\n";
    echo "Position updated: {$positionToModify->position_name} is now a division head position.\n";
    
} catch (Exception $e) {
    echo "Error assigning division head: " . $e->getMessage() . "\n";
}

// Verify the assignment worked
$updatedDivisionHead = \App\Models\Employee::whereHas('positions', function($query) use ($collegeOfEngineering) {
    $query->where('is_division_head', true)
          ->where('is_primary', true)
          ->where('division_id', $collegeOfEngineering->id);
})->with('positions')->first();

if ($updatedDivisionHead) {
    $pos = $updatedDivisionHead->positions->firstWhere('is_primary', true);
    echo "\n✅ VERIFICATION: Successfully created division head for College of Engineering:\n";
    echo "  Name: {$updatedDivisionHead->first_name} {$updatedDivisionHead->last_name}\n";
    echo "  Position: {$pos->position_name}\n";
    echo "  Division: {$pos->division->division_name}\n";
    echo "  Is Division Head: " . ($pos->is_division_head ? 'Yes' : 'No') . "\n";
} else {
    echo "\n❌ VERIFICATION: Failed to create division head for College of Engineering.\n";
}

?>