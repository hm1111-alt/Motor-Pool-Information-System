<?php

require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

// Create a service container
$container = new Container;
$capsule = new Capsule($container);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Load .env file
$env = parse_ini_file('.env');

// Configure database connection
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $env['DB_HOST'] ?? '127.0.0.1',
    'port'      => $env['DB_PORT'] ?? '3306',
    'database'  => $env['DB_DATABASE'] ?? 'motorpool',
    'username'  => $env['DB_USERNAME'] ?? 'root',
    'password'  => $env['DB_PASSWORD'] ?? '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Test the database connection
try {
    $capsule->getConnection()->getPdo();
    echo "Database connection successful!\n\n";
    
    // Get all drivers with their users
    $drivers = $capsule->table('drivers')->get();
    
    echo "Drivers found: " . count($drivers) . "\n\n";
    
    foreach ($drivers as $driver) {
        echo "Driver ID: " . $driver->id . "\n";
        echo "Name: " . $driver->full_name . "\n";
        echo "User ID: " . $driver->user_id . "\n";
        
        // Get the associated user
        if ($driver->user_id) {
            $user = $capsule->table('users')->where('id', $driver->user_id)->first();
            if ($user) {
                echo "User Name: " . $user->name . "\n";
                echo "User Email: " . $user->email . "\n";
                echo "User Contact: " . ($user->contact_num ?? 'NULL') . "\n";
            } else {
                echo "User not found!\n";
            }
        } else {
            echo "No user_id assigned!\n";
        }
        echo "---\n";
    }
    
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}