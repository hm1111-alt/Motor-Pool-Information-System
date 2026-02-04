<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Mock a request
$request = Illuminate\Http\Request::create(
    '/admin/employees/get-units-by-division',
    'GET',
    ['division_id' => 1]
);

// We need to bypass auth middleware or mock a user
// Since it's hard to bypass middleware in a simple script without proper setup,
// we can try to resolve the controller and call the method directly to verify the logic inside the app context.

$controller = $app->make(\App\Http\Controllers\EmployeeController::class);
$response = $controller->getUnitsByDivision($request);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
