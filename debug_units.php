<?php
define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Manually test the query
echo "Testing Unit query...\n";

try {
    $divisionId = \App\Models\Division::first()->id_division;
    echo "Using Division ID: " . $divisionId . "\n";
    
    $units = \App\Models\Unit::where('unit_division', $divisionId)->get();
    
    echo "Found " . $units->count() . " units.\n";
    foreach ($units as $unit) {
        echo "Unit: " . $unit->unit_name . " (ID: " . $unit->id_unit . ")\n";
    }
    
    // Check if there are any units at all
    $allUnits = \App\Models\Unit::count();
    echo "Total units in database: " . $allUnits . "\n";
    
    if ($allUnits > 0) {
        $firstUnit = \App\Models\Unit::first();
        echo "First unit sample: " . json_encode($firstUnit) . "\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
