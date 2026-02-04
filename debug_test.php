<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing database connection and models...\n";

try {
    // Test offices
    $offices = \App\Models\Office::all();
    echo "Offices count: " . $offices->count() . "\n";
    
    // Test divisions for office 1
    $divisions = \App\Models\Division::where('office_id', 1)->get();
    echo "Divisions for office 1: " . $divisions->count() . "\n";
    if ($divisions->count() > 0) {
        echo "First division: " . $divisions[0]->division_name . "\n";
    }
    
    // Test units for division 1
    $units = \App\Models\Unit::where('unit_division', 1)->get();
    echo "Units for division 1: " . $units->count() . "\n";
    if ($units->count() > 0) {
        echo "First unit: " . $units[0]->unit_name . "\n";
    }
    
    // Test the AJAX controller method directly
    echo "\nTesting controller method...\n";
    $request = new \Illuminate\Http\Request();
    $request->merge(['office_id' => 1]);
    
    $controller = new \App\Http\Controllers\EmployeeController();
    $response = $controller->getDivisionsByOffice($request);
    
    echo "Controller response status: " . $response->getStatusCode() . "\n";
    echo "Controller response content length: " . strlen($response->getContent()) . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}