<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<h1>AJAX Endpoint Tests</h1>\n";

try {
    // Test the getDivisionsByOffice method directly
    echo "<h2>Testing getDivisionsByOffice method</h2>\n";
    
    $request = new Illuminate\Http\Request();
    $request->merge(['office_id' => 1]);
    
    $controller = new App\Http\Controllers\EmployeeController();
    $response = $controller->getDivisionsByOffice($request);
    
    echo "<p>Response Status: " . $response->getStatusCode() . "</p>\n";
    echo "<p>Response Content:</p>\n";
    echo "<pre>" . htmlspecialchars($response->getContent()) . "</pre>\n";
    
    // Test getUnitsByDivision
    echo "<h2>Testing getUnitsByDivision method</h2>\n";
    
    $request2 = new Illuminate\Http\Request();
    $request2->merge(['division_id' => 1]); // Use first division ID
    
    $response2 = $controller->getUnitsByDivision($request2);
    
    echo "<p>Response Status: " . $response2->getStatusCode() . "</p>\n";
    echo "<p>Response Content:</p>\n";
    echo "<pre>" . htmlspecialchars($response2->getContent()) . "</pre>\n";
    
    // Test getSubunitsByUnit
    echo "<h2>Testing getSubunitsByUnit method</h2>\n";
    
    // First get a unit to test with
    $units = App\Models\Unit::where('unit_division', 1)->limit(1)->get();
    if ($units->count() > 0) {
        $unitId = $units[0]->id_unit;
        
        $request3 = new Illuminate\Http\Request();
        $request3->merge(['unit_id' => $unitId]);
        
        $response3 = $controller->getSubunitsByUnit($request3);
        
        echo "<p>Response Status: " . $response3->getStatusCode() . "</p>\n";
        echo "<p>Response Content:</p>\n";
        echo "<pre>" . htmlspecialchars($response3->getContent()) . "</pre>\n";
    } else {
        echo "<p>No units found for testing subunits</p>\n";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>\n";
    echo "<pre>" . $e->getTraceAsString() . "</pre>\n";
}