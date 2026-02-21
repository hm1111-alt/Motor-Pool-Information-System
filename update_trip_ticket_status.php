<?php
// Script to update trip ticket status from 'Issued' to 'Approved'

require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Create the Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';

// Bootstrap the application
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Create a request to initialize the application properly
$app->bind(Illuminate\Contracts\Http\Kernel::class, App\Http\Kernel::class);
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Now run the database operations
try {
    // First, update any 'Issued' status records to 'Approved' to avoid enum constraint issues
    $updatedRows = DB::table('trip_tickets')
        ->where('status', 'Issued')
        ->update(['status' => 'Approved']);
    
    echo "Updated {$updatedRows} trip ticket(s) from 'Issued' to 'Approved' status.\n";
    
    // Now modify the enum to include 'Approved' instead of 'Issued' using raw SQL
    DB::statement("ALTER TABLE trip_tickets MODIFY status ENUM('Pending', 'Approved', 'Completed', 'Cancelled') DEFAULT 'Pending'");
    
    echo "Successfully updated the status enum to use 'Approved' instead of 'Issued'.\n";
    echo "Trip ticket status update completed.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}