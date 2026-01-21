<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Checking Roy Searca's User Account ===\n\n";

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
echo "  User ID: {$roy->user_id}\n";

// Check if Roy is linked to a User account via user_id
if ($roy->user_id) {
    $user = \App\Models\User::find($roy->user_id);
    if ($user) {
        echo "Associated User Account:\n";
        echo "  User ID: {$user->id}\n";
        echo "  Email: {$user->email}\n";
        echo "  Name: {$user->name}\n";
        echo "  Contact: {$user->contact_num}\n";
        echo "  Role: {$user->role}\n";
    } else {
        echo "⚠️  User account with ID {$roy->user_id} not found!\n";
    }
} else {
    echo "⚠️  No user_id assigned to this employee!\n";
    echo "This employee does not have an associated user account.\n";
}

// Check positions
echo "\nPositions:\n";
foreach ($roy->positions as $position) {
    echo "  - Position: {$position->position_name}\n";
    echo "    Division ID: {$position->division_id}\n";
    echo "    Is Primary: " . ($position->is_primary ? 'Yes' : 'No') . "\n";
    echo "    Is Division Head: " . ($position->is_division_head ? 'Yes' : 'No') . "\n";
    echo "\n";
}

// Check if the employee has a user relationship properly set up
$employeeWithUser = \App\Models\Employee::with('user')->find($roy->id);
if ($employeeWithUser->user) {
    echo "✅ Employee has proper user relationship\n";
    echo "  User email: {$employeeWithUser->user->email}\n";
} else {
    echo "❌ Employee does not have a user relationship\n";
}

?>