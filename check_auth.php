<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Create a fake request to check if we can simulate the authentication state
$request = Request::createFromGlobals();

echo "Checking authentication state:\n";
echo "Is authenticated: " . (Auth::check() ? 'Yes' : 'No') . "\n";

if (Auth::check()) {
    $user = Auth::user();
    echo "User: " . $user->name . " (ID: " . $user->id . ")\n";
    
    if (isset($user->employee)) {
        $employee = $user->employee;
        echo "Employee: {$employee->first_name} {$employee->last_name}\n";
        echo "Is division head: " . ($employee->is_divisionhead ? 'Yes' : 'No') . "\n";
        echo "Is head: " . ($employee->is_head ? 'Yes' : 'No') . "\n";
        echo "Is VP: " . ($employee->is_vp ? 'Yes' : 'No') . "\n";
        echo "Is president: " . ($employee->is_president ? 'Yes' : 'No') . "\n";
    } else {
        echo "User has no employee record\n";
    }
} else {
    echo "No user is currently authenticated.\n";
    echo "The 403 error is likely because no user is logged in.\n";
}

echo "\nThis script must be run in the context of an authenticated user to work properly.\n";