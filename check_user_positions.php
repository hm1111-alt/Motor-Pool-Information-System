<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Checking Roy Searca's Positions and Permissions ===\n\n";

// Find Roy Searca Jose Dela Cruz
$roy = \App\Models\Employee::where('first_name', 'Roy')
    ->where('last_name', 'Searca')
    ->first();

if (!$roy) {
    echo "Roy Searca not found in the database\n";
    exit;
}

echo "Roy Searca Jose Dela Cruz (ID: {$roy->id}):\n";
echo "  is_divisionhead: " . ($roy->is_divisionhead ? 'Yes' : 'No') . "\n";
echo "  Positions:\n";

foreach ($roy->positions as $position) {
    echo "    - Position: {$position->position_name}\n";
    echo "      Division ID: {$position->division_id}\n";
    echo "      Unit ID: {$position->unit_id}\n";
    echo "      Office ID: {$position->office_id}\n";
    echo "      Is Primary: " . ($position->is_primary ? 'Yes' : 'No') . "\n";
    echo "      Is Division Head: " . ($position->is_division_head ? 'Yes' : 'No') . "\n";
    echo "      Is VP: " . ($position->is_vp ? 'Yes' : 'No') . "\n";
    echo "      Is President: " . ($position->is_president ? 'Yes' : 'No') . "\n";
    echo "\n";
}

// Check if Roy is linked to a User account
$user = \App\Models\User::where('employee_id', $roy->id)->first();

if ($user) {
    echo "Associated User Account:\n";
    echo "  User ID: {$user->id}\n";
    echo "  Email: {$user->email}\n";
    echo "  Name: {$user->name}\n";
} else {
    echo "⚠️  No user account found associated with this employee!\n";
    echo "This could be the issue - the employee exists but has no associated user account.\n";
}

// Check the specific division head position
$divisionHeadPositions = $roy->positions->filter(function($position) {
    return $position->is_division_head == true;
});

echo "\nDivision Head Positions for Roy:\n";
foreach ($divisionHeadPositions as $position) {
    echo "  - Position: {$position->position_name}\n";
    echo "    Division: {$position->division->division_name}\n";
    echo "    Division ID: {$position->division_id}\n";
    echo "    Is Primary: " . ($position->is_primary ? 'Yes' : 'No') . "\n";
}

// Check if there's a primary position that is a division head
$primaryPosition = $roy->positions->firstWhere('is_primary', true);
echo "\nPrimary Position:\n";
if ($primaryPosition) {
    echo "  Position: {$primaryPosition->position_name}\n";
    echo "  Division: {$primaryPosition->division->division_name}\n";
    echo "  Is Division Head: " . ($primaryPosition->is_division_head ? 'Yes' : 'No') . "\n";
} else {
    echo "  No primary position set!\n";
}

?>