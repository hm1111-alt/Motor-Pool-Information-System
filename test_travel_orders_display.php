<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;

try {
    // Test fetching approved travel orders
    $travelOrders = TravelOrder::where('status', 'approved')
        ->with('employee')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    echo "Found " . $travelOrders->count() . " approved travel orders:\n\n";
    
    foreach ($travelOrders as $order) {
        echo "ID: " . $order->id . "\n";
        echo "Employee: " . ($order->employee ? $order->employee->first_name . ' ' . $order->employee->last_name : 'N/A') . "\n";
        echo "Purpose: " . ($order->purpose ?: 'N/A') . "\n";
        echo "Destination: " . ($order->destination ?: 'N/A') . "\n";
        echo "Date Needed: " . ($order->date_needed ?: 'N/A') . "\n";
        echo "Status: " . $order->status . "\n";
        echo "---\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}