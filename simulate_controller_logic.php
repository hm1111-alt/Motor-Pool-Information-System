<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Simulating DivisionHeadTravelOrderController Logic Step-by-Step ===\n\n";

// Get the travel order
$travelOrder = \App\Models\TravelOrder::find(124);
if (!$travelOrder) {
    echo "Travel order ID 124 not found\n";
    exit;
}

echo "Travel Order ID 124:\n";
echo "  Employee ID: {$travelOrder->employee_id}\n";
echo "  Position: {$travelOrder->position->position_name}\n";
echo "  Division: {$travelOrder->position->division->division_name} (ID: {$travelOrder->position->division_id})\n";
echo "  Head Approved: " . ($travelOrder->head_approved ? 'Yes' : 'No') . "\n";
echo "  Division Head Approved: " . ($travelOrder->divisionhead_approved === null ? 'Pending' : ($travelOrder->divisionhead_approved ? 'Yes' : 'No')) . "\n";

// Now, let's simulate what happens in the controller when a user tries to approve
// In a real scenario, we'd get the currently authenticated user, but let's simulate it

// First, let's find the division head for the same division as the travel order
$travelOrderDivisionId = $travelOrder->position->division_id; // This should be 18
echo "\nTravel order belongs to Division ID: {$travelOrderDivisionId}\n";

// Find the division head for this division
$divisionHead = \App\Models\Employee::whereHas('positions', function($query) use ($travelOrderDivisionId) {
    $query->where('is_division_head', true)
          ->where('is_primary', true)
          ->where('division_id', $travelOrderDivisionId);
})->with('positions')->first();

if (!$divisionHead) {
    echo "ERROR: No division head found for Division ID {$travelOrderDivisionId}\n";
    exit;
}

echo "\nFound Division Head:\n";
echo "  Name: {$divisionHead->first_name} {$divisionHead->last_name}\n";
echo "  Employee ID: {$divisionHead->id}\n";
echo "  User ID: {$divisionHead->user_id}\n";
echo "  Is Division Head: " . ($divisionHead->is_divisionhead ? 'Yes' : 'No') . "\n";

// Get the division head's user account
$divisionHeadUser = \App\Models\User::find($divisionHead->user_id);
echo "  User Email: {$divisionHeadUser->email}\n";

// Now let's simulate the exact authorization checks from the controller
echo "\n=== Exact Controller Authorization Checks ===\n";

// This is what happens inside the controller method
echo "Inside approve method:\n";

// $user = Auth::user();  // In simulation, this would be the division head's user
// $employee = $user->employee;  // This would be the division head's employee record

$employee = $divisionHead;  // Simulating the authenticated employee

echo "1. Employee is division head check:\n";
echo "   \$employee->is_divisionhead = " . ($employee->is_divisionhead ? 'true' : 'false') . "\n";
$isDivisionHead = $employee->is_divisionhead;
echo "   Result: " . ($isDivisionHead ? 'PASS' : 'FAIL') . "\n";

echo "\n2. Getting division head's primary position:\n";
$divisionHeadPrimaryPosition = $employee->positions()->where('is_primary', true)->first();
$divisionHeadDivisionId = $divisionHeadPrimaryPosition ? $divisionHeadPrimaryPosition->division_id : null;
echo "   Division head's division ID: " . ($divisionHeadDivisionId ?? 'NULL') . "\n";

echo "\n3. Getting travel order position:\n";
$travelOrderPosition = $travelOrder->position;
echo "   Travel order position: " . ($travelOrderPosition ? $travelOrderPosition->position_name : 'NULL') . "\n";

echo "\n4. Checking if travel order has position:\n";
if (!$travelOrderPosition) {
    echo "   No position assigned - would return 403\n";
} else {
    echo "   Position exists - continuing\n";
    
    echo "\n5. Division comparison check:\n";
    $travelOrderDivisionId = $travelOrderPosition->division_id;
    echo "   Travel order division ID: {$travelOrderDivisionId}\n";
    echo "   Division head division ID: {$divisionHeadDivisionId}\n";
    $sameDivision = $travelOrderDivisionId === $divisionHeadDivisionId;
    echo "   Comparison: {$travelOrderDivisionId} === {$divisionHeadDivisionId} = " . ($sameDivision ? 'true' : 'false') . "\n";
    echo "   Result: " . ($sameDivision ? 'PASS' : 'FAIL') . "\n";
    
    echo "\n6. Checking if travel order is from eligible employee (not division head/VP/president):\n";
    $isEligible = !($travelOrderPosition->is_division_head || $travelOrderPosition->is_vp || $travelOrderPosition->is_president);
    echo "   Travel order from division head: " . ($travelOrderPosition->is_division_head ? 'Yes' : 'No') . "\n";
    echo "   Travel order from VP: " . ($travelOrderPosition->is_vp ? 'Yes' : 'No') . "\n";
    echo "   Travel order from President: " . ($travelOrderPosition->is_president ? 'Yes' : 'No') . "\n";
    echo "   Eligible: " . ($isEligible ? 'Yes' : 'No') . "\n";
    echo "   Result: " . ($isEligible ? 'PASS' : 'FAIL') . "\n";
    
    echo "\n7. Checking if head has approved:\n";
    $headApproved = $travelOrder->head_approved;
    echo "   Head approved: " . ($headApproved ? 'Yes' : 'No') . "\n";
    echo "   Result: " . ($headApproved ? 'PASS' : 'FAIL') . "\n";
    
    echo "\n8. Checking if not already approved by division head:\n";
    $notAlreadyDHApproved = is_null($travelOrder->divisionhead_approved);
    echo "   Division head approved: " . ($travelOrder->divisionhead_approved === null ? 'Pending' : ($travelOrder->divisionhead_approved ? 'Yes' : 'No')) . "\n";
    echo "   Result: " . ($notAlreadyDHApproved ? 'PASS' : 'FAIL') . "\n";
    
    echo "\n9. Checking if not approved by higher authorities:\n";
    $notApprovedByHigher = is_null($travelOrder->vp_approved) && is_null($travelOrder->president_approved);
    echo "   VP approved: " . ($travelOrder->vp_approved === null ? 'Pending' : ($travelOrder->vp_approved ? 'Yes' : 'No')) . "\n";
    echo "   President approved: " . ($travelOrder->president_approved === null ? 'Pending' : ($travelOrder->president_approved ? 'Yes' : 'No')) . "\n";
    echo "   Result: " . ($notApprovedByHigher ? 'PASS' : 'FAIL') . "\n";
    
    $allChecks = $isDivisionHead && $sameDivision && $isEligible && $headApproved && $notAlreadyDHApproved && $notApprovedByHigher;
    echo "\nFINAL RESULT: " . ($allChecks ? 'ALL CHECKS PASSED - APPROVAL SHOULD WORK!' : 'SOME CHECK FAILED - WOULD GET 403') . "\n";
    
    if ($allChecks) {
        echo "\n✅ The system is properly configured. If you're still getting 403, make sure:\n";
        echo "   1. You are logged in as: {$divisionHeadUser->email}\n";
        echo "   2. You are accessing the correct URL: http://127.0.0.1:8000/travel-orders/approvals/divisionhead\n";
        echo "   3. You clicked the approve button from that interface (which should now use the correct route)\n";
    }
}

?>