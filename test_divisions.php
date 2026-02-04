<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test getting divisions for office ID 1
    $divisions = App\Models\Division::where('office_id', 1)->get();
    
    echo "Found " . $divisions->count() . " divisions for office ID 1\n";
    
    foreach ($divisions as $division) {
        echo "- " . $division->division_name . " (ID: " . $division->id_division . ")\n";
    }
    
    // Test the AJAX method directly
    $request = new Illuminate\Http\Request();
    $request->merge(['office_id' => 1]);
    
    $controller = new App\Http\Controllers\EmployeeController();
    $response = $controller->getDivisionsByOffice($request);
    
    echo "\nAJAX Response:\n";
    echo $response->getContent();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}