<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

// Check division head travel orders
try {
    echo "=== Checking Division Head Travel Orders ===\n\n";
    
    // Get division head
    $divisionHead = Employee::where('is_divisionhead', 1)->first();
    
    if (!$divisionHead) {
        echo "No division head found\n";
        exit;
    }
    
    echo "Division Head: " . $divisionHead->first_name . " " . $divisionHead->last_name . " (ID: " . $divisionHead->id . ", Office ID: " . $divisionHead->office_id . ")\n";
    
    // Get travel orders for this division head
    $travelOrders = TravelOrder::where('employee_id', $divisionHead->id)->get();
    
    echo "Found " . count($travelOrders) . " travel orders for this division head:\n";
    
    foreach ($travelOrders as $order) {
        echo "- ID: " . $order->id . ", Destination: " . $order->destination . ", Status: " . $order->status . "\n";
        echo "  VP Approved: " . var_export($order->vp_approved, true) . "\n";
        echo "  President Approved: " . var_export($order->president_approved, true) . "\n";
        echo "  Remarks: " . $order->remarks . "\n";
        echo "  ---\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}