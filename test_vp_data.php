<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

// Check what data we have
try {
    echo "=== Checking Travel Orders ===\n";
    
    // Check all travel orders with their employee info
    $travelOrders = TravelOrder::with('employee')->get();
    
    echo "Total travel orders: " . count($travelOrders) . "\n\n";
    
    foreach ($travelOrders as $travelOrder) {
        echo "Travel Order ID: " . $travelOrder->id . "\n";
        echo "  Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . " (ID: " . $travelOrder->employee->id . ")\n";
        echo "  Is Head: " . ($travelOrder->employee->is_head ? 'Yes' : 'No') . "\n";
        echo "  Office ID: " . $travelOrder->employee->office_id . "\n";
        echo "  Division ID: " . $travelOrder->employee->division_id . "\n";
        echo "  Head Approved: " . var_export($travelOrder->head_approved, true) . "\n";
        echo "  Head Approved At: " . ($travelOrder->head_approved_at ? $travelOrder->head_approved_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
        echo "  Division Head Approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
        echo "  Division Head Approved At: " . ($travelOrder->divisionhead_approved_at ? $travelOrder->divisionhead_approved_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
        echo "  VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
        echo "  VP Approved At: " . ($travelOrder->vp_approved_at ? $travelOrder->vp_approved_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
        echo "  Status: " . $travelOrder->status . "\n";
        echo "  Remarks: " . $travelOrder->remarks . "\n";
        echo "---\n";
    }
    
    echo "\n=== Checking VP Employees ===\n";
    $vps = Employee::where('is_vp', 1)->get();
    foreach ($vps as $vp) {
        echo "VP: " . $vp->first_name . " " . $vp->last_name . " (ID: " . $vp->id . ")\n";
        echo "  Office ID: " . $vp->office_id . "\n";
        echo "  Division ID: " . $vp->division_id . "\n";
        echo "---\n";
    }
    
    echo "\n=== Checking Division Heads ===\n";
    $divisionHeads = Employee::where('is_divisionhead', 1)->get();
    foreach ($divisionHeads as $dh) {
        echo "Division Head: " . $dh->first_name . " " . $dh->last_name . " (ID: " . $dh->id . ")\n";
        echo "  Office ID: " . $dh->office_id . "\n";
        echo "  Division ID: " . $dh->division_id . "\n";
        echo "---\n";
    }
    
    echo "\n=== Checking Heads ===\n";
    $heads = Employee::where('is_head', 1)->get();
    foreach ($heads as $head) {
        echo "Head: " . $head->first_name . " " . $head->last_name . " (ID: " . $head->id . ")\n";
        echo "  Office ID: " . $head->office_id . "\n";
        echo "  Division ID: " . $head->division_id . "\n";
        echo "---\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}