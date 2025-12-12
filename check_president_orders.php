<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

// Check president travel orders
try {
    // Get a president employee
    $presidentEmployee = Employee::where('is_president', 1)->first();
    
    if (!$presidentEmployee) {
        echo "No president found\n";
        exit;
    }
    
    echo "President ID: " . $presidentEmployee->id . "\n";
    
    // Get all travel orders for this president
    $orders = TravelOrder::where('employee_id', $presidentEmployee->id)->get();
    
    echo "Total travel orders: " . count($orders) . "\n";
    
    foreach ($orders as $order) {
        echo "ID: " . $order->id . ", Status: " . $order->status . ", President Approved: " . var_export($order->president_approved, true) . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}