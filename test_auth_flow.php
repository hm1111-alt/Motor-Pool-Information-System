<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Authentication Flow ===\n\n";

// Find the division head user
$divisionHeadUser = \App\Models\User::where('email', 'roysearcajose.delacruz@clsu2.edu.ph')->first();

if (!$divisionHeadUser) {
    echo "ERROR: Division head user account not found\n";
    exit;
}

echo "Division Head User Found:\n";
echo "  ID: {$divisionHeadUser->id}\n";
echo "  Email: {$divisionHeadUser->email}\n";
echo "  Name: {$divisionHeadUser->name}\n";

// Find the associated employee
$employee = \App\Models\Employee::find($divisionHeadUser->id);

if (!$employee) {
    echo "ERROR: No employee found for this user\n";
    exit;
}

echo "Associated Employee:\n";
echo "  ID: {$employee->id}\n";
echo "  Name: {$employee->first_name} {$employee->last_name}\n";
echo "  Is Division Head: " . ($employee->is_divisionhead ? 'Yes' : 'No') . "\n";

// Check positions
$primaryPosition = $employee->positions()->where('is_primary', true)->first();
if ($primaryPosition) {
    echo "Primary Position:\n";
    echo "  Position: {$primaryPosition->position_name}\n";
    echo "  Division ID: {$primaryPosition->division_id}\n";
    echo "  Is Division Head: " . ($primaryPosition->is_division_head ? 'Yes' : 'No') . "\n";
} else {
    echo "No primary position found\n";
}

echo "\n=== Manual Test Instructions ===\n";
echo "To test the approval flow:\n";
echo "1. Make sure you are logged out of the system\n";
echo "2. Go to the login page\n";
echo "3. Log in with:\n";
echo "   Email: roysearcajose.delacruz@clsu2.edu.ph\n";
echo "   Password: [use the password set for this account]\n";
echo "4. After logging in, go to: http://127.0.0.1:8000/travel-orders/approvals/divisionhead\n";
echo "5. Find travel order #124 and click Approve\n";

?>