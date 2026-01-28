<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $foreignKeys = \DB::select("
        SELECT CONSTRAINT_NAME 
        FROM information_schema.TABLE_CONSTRAINTS 
        WHERE CONSTRAINT_SCHEMA = DATABASE()
            AND TABLE_NAME = 'drivers'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
    ");
    
    echo "Foreign Keys in drivers table:\n";
    foreach ($foreignKeys as $fk) {
        echo "- {$fk->CONSTRAINT_NAME}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}