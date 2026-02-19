<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    if (!Schema::hasColumn('vehicles', 'is_archived')) {
        DB::statement('ALTER TABLE vehicles ADD COLUMN is_archived BOOLEAN DEFAULT FALSE AFTER status');
        echo "Column 'is_archived' added successfully to vehicles table.\n";
    } else {
        echo "Column 'is_archived' already exists in vehicles table.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}