<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test if divisionhead_approved column exists
try {
    $result = DB::select("SHOW COLUMNS FROM travel_orders LIKE 'divisionhead_approved'");
    if (count($result) > 0) {
        echo "divisionhead_approved column exists\n";
    } else {
        echo "divisionhead_approved column does not exist\n";
    }
    
    $result = DB::select("SHOW COLUMNS FROM travel_orders LIKE 'divisionhead_approved_at'");
    if (count($result) > 0) {
        echo "divisionhead_approved_at column exists\n";
    } else {
        echo "divisionhead_approved_at column does not exist\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}