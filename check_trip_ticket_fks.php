<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $foreignKeys = \DB::select("
        SELECT 
            CONSTRAINT_NAME,
            COLUMN_NAME
        FROM 
            information_schema.KEY_COLUMN_USAGE 
        WHERE 
            TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'trip_tickets'
            AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    echo "Foreign Keys in trip_tickets table:\n";
    foreach ($foreignKeys as $fk) {
        echo "- {$fk->CONSTRAINT_NAME}: {$fk->COLUMN_NAME}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}