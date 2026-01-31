<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Offices: " . App\Models\Office::count() . "\n";
echo "Divisions: " . App\Models\Division::count() . "\n";
echo "Units: " . App\Models\Unit::count() . "\n";
echo "Subunits: " . App\Models\Subunit::count() . "\n";

// Test a specific relationship
$office = App\Models\Office::first();
if ($office) {
    echo "\nFirst office: " . $office->office_name . "\n";
    echo "Divisions in this office: " . $office->divisions->count() . "\n";
    
    if ($office->divisions->count() > 0) {
        $division = $office->divisions->first();
        echo "First division: " . $division->division_name . "\n";
        echo "Units in this division: " . $division->units->count() . "\n";
        
        if ($division->units->count() > 0) {
            $unit = $division->units->first();
            echo "First unit: " . $unit->unit_name . "\n";
            echo "Subunits in this unit: " . $unit->subunits->count() . "\n";
        }
    }
}
?>