<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;

try {
    echo "Testing driver creation...\n";
    
    // Test data
    $testData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'johndoe' . time() . '@test.com',
        'password' => 'password123',
        'contact_num' => '09123456789',
        'address' => 'Test Address',
        'position' => 'Test Driver',
        'official_station' => 'Test Station'
    ];
    
    echo "Creating user account...\n";
    $user = User::create([
        'name' => $testData['first_name'] . ' ' . $testData['last_name'],
        'email' => $testData['email'],
        'password' => Hash::make($testData['password']),
        'role' => 'driver'
    ]);
    echo "User created with ID: " . $user->id . "\n";
    
    echo "Creating driver profile...\n";
    $fullName = $testData['first_name'] . ' ' . $testData['last_name'];
    $driver = Driver::create([
        'user_id' => $user->id,
        'firsts_name' => $testData['first_name'],
        'last_name' => $testData['last_name'],
        'full_name' => $fullName,
        'full_name2' => $fullName,
        'contact_num' => $testData['contact_num'],
        'email' => $testData['email'],
        'address' => $testData['address'],
        'position' => $testData['position'],
        'official_station' => $testData['official_station'],
        'availability_status' => 'Available',
    ]);
    echo "Driver created with ID: " . $driver->id . "\n";
    
    echo "✅ Driver creation successful!\n";
    echo "User ID: " . $user->id . "\n";
    echo "Driver ID: " . $driver->id . "\n";
    echo "Name: " . $driver->full_name . "\n";
    echo "Email: " . $driver->email . "\n";
    echo "Contact: " . $driver->contact_num . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "File: " . $e->getFile() . "\n";
}