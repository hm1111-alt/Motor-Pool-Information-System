<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Check if travel_orders table exists
    $exists = DB::select("SHOW TABLES LIKE 'travel_orders'");
    
    if (count($exists) > 0) {
        echo "Table 'travel_orders' exists.\n";
        
        // Check if president_approved column exists
        $columns = DB::select("SHOW COLUMNS FROM travel_orders LIKE 'president_approved'");
        if (count($columns) > 0) {
            echo "Column 'president_approved' already exists.\n";
        } else {
            echo "Column 'president_approved' does not exist.\n";
        }
        
        // Check if president_approved_at column exists
        $columns = DB::select("SHOW COLUMNS FROM travel_orders LIKE 'president_approved_at'");
        if (count($columns) > 0) {
            echo "Column 'president_approved_at' already exists.\n";
        } else {
            echo "Column 'president_approved_at' does not exist.\n";
        }
    } else {
        echo "Table 'travel_orders' does not exist.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}