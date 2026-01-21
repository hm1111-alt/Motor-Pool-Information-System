<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Fixing Approval Flow for Travel Order 124 ===\n\n";

// Get the specific travel order
$order124 = \App\Models\TravelOrder::find(124);
if (!$order124) {
    echo "Travel order ID 124 not found\n";
    exit;
}

echo "Travel Order ID 124 Details:\n";
echo "  Destination: {$order124->destination}\n";
echo "  Status: {$order124->status}\n";
echo "  Head Approved: " . ($order124->head_approved ? 'Yes' : 'No') . "\n";
echo "  Division Head Approved: " . ($order124->divisionhead_approved === null ? 'Pending' : ($order124->divisionhead_approved ? 'Yes' : 'No')) . "\n";
echo "  VP Approved: " . ($order124->vp_approved === null ? 'Pending' : ($order124->vp_approved ? 'Yes' : 'No')) . "\n";
echo "  President Approved: " . ($order124->president_approved === null ? 'Pending' : ($order124->president_approved ? 'Yes' : 'No')) . "\n";

if ($order124->position) {
    echo "  Position: {$order124->position->position_name}\n";
    echo "  Division ID: {$order124->position->division_id}\n";
    if ($order124->position->division) {
        echo "  Division Name: {$order124->position->division->division_name}\n";
    }
} else {
    echo "  NO POSITION ASSIGNED\n";
}

echo "\n=== Current Approval Stage Analysis ===\n";

$stage = '';
$nextApprover = '';

if (!$order124->head_approved) {
    $stage = 'Head Approval Stage';
    $nextApprover = 'Unit Head';
} elseif (is_null($order124->divisionhead_approved)) {
    $stage = 'Division Head Approval Stage';
    $nextApprover = 'Division Head';
} elseif (is_null($order124->vp_approved)) {
    $stage = 'VP Approval Stage';
    $nextApprover = 'Vice President';
} elseif (is_null($order124->president_approved)) {
    $stage = 'President Approval Stage';
    $nextApprover = 'President';
} else {
    $stage = 'Fully Approved';
    $nextApprover = 'None';
}

echo "Current Stage: {$stage}\n";
echo "Next Approver: {$nextApprover}\n";

echo "\n=== Correct URLs for Approval ===\n";

if ($stage === 'Head Approval Stage') {
    echo "Use these URLs:\n";
    echo "  Approval Interface: http://127.0.0.1:8000/travel-orders/approvals/head\n";
    echo "  Direct Approval: http://127.0.0.1:8000/travel-orders/124/approve/head\n";
} elseif ($stage === 'Division Head Approval Stage') {
    echo "Use these URLs:\n";
    echo "  Approval Interface: http://127.0.0.1:8000/travel-orders/approvals/divisionhead\n";
    echo "  Direct Approval: http://127.0.0.1:8000/travel-orders/124/approve/divisionhead\n";
} elseif ($stage === 'VP Approval Stage') {
    echo "Use these URLs:\n";
    echo "  Approval Interface: http://127.0.0.1:8000/travel-orders/approvals/vp\n";
    echo "  Direct Approval: http://127.0.0.1:8000/travel-orders/124/approve/vp\n";
} elseif ($stage === 'President Approval Stage') {
    echo "Use these URLs:\n";
    echo "  Approval Interface: http://127.0.0.1:8000/travel-orders/approvals/president\n";
    echo "  Direct Approval: http://127.0.0.1:8000/travel-orders/124/approve/president\n";
} else {
    echo "Travel order is fully approved or cancelled.\n";
}

echo "\n=== Problem Diagnosis ===\n";

if ($stage === 'Division Head Approval Stage') {
    // Check if there's a division head for this division
    $divisionId = $order124->position->division_id ?? null;
    if ($divisionId) {
        $divisionHead = \App\Models\Employee::whereHas('positions', function($query) use ($divisionId) {
            $query->where('is_division_head', true)
                  ->where('is_primary', true)
                  ->where('division_id', $divisionId);
        })->with('positions')->first();
        
        if ($divisionHead) {
            echo "✅ Found division head for Division ID {$divisionId}: {$divisionHead->first_name} {$divisionHead->last_name}\n";
            echo "   You should log in as this user to approve the travel order.\n";
        } else {
            echo "❌ No division head found for Division ID {$divisionId}\n";
            echo "   Solution: Assign a division head role to someone in this division.\n";
        }
    }
}

echo "\n=== Solution ===\n";
echo "Since travel order 124 is at the {$stage}, you should:\n";
echo "1. Navigate to the correct approval interface URL above\n";
echo "2. Log in as the appropriate approver ({$nextApprover})\n";
echo "3. Find travel order #124 in the list\n";
echo "4. Click Approve or Reject\n";

?>