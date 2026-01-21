<?php

// Simple script to test form submission
require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Create sample data that mimics a form submission
$postData = [
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => 'testuser' . time() . '@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'position_name' => 'Test Position',
    'sex' => 'M',
    'role' => 'vp',
    'emp_status' => '1', // This should be sent from the hidden field
];

echo "Testing form data validation...\n";

// Validate against the same rules as in the controller
$validator = Validator::make($postData, [
    'first_name' => 'required|string|max:255',
    'last_name' => 'required|string|max:255',
    'middle_initial' => 'nullable|string|max:10',
    'ext_name' => 'nullable|string|max:10',
    'sex' => 'required|string|in:M,F',
    'prefix' => 'nullable|string|max:10',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|string|min:8|confirmed',
    'position_name' => 'required|string|max:255',
    'office_id' => 'nullable|exists:offices,id',
    'division_id' => 'nullable|exists:divisions,id',
    'unit_id' => 'nullable|exists:units,id',
    'subunit_id' => 'nullable|exists:subunits,id',
    'class_id' => 'nullable|exists:class,id',
    'role' => 'nullable|in:unit_head,division_head,vp,president',
]);

if ($validator->fails()) {
    echo "Validation failed:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "- " . $error . "\n";
    }
} else {
    echo "Validation passed!\n";
    echo "Form data is valid and should be accepted by the controller.\n";
}

// Also check if there might be any issues with required fields
echo "\nChecking for potentially missing required fields:\n";
$requiredFields = [
    'first_name',
    'last_name',
    'email',
    'password',
    'position_name',
    'sex'
];

foreach ($requiredFields as $field) {
    if (!isset($postData[$field]) || empty($postData[$field])) {
        echo "Missing required field: $field\n";
    }
}

echo "All required fields present.\n";