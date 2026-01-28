<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = App\Models\User::all();

echo "All users:\n";
foreach($users as $user) {
    echo "ID: " . $user->id . " Name: " . $user->name . " Role: " . $user->role . "\n";
    $employee = $user->employee;
    if ($employee) {
        echo "  -> Has employee: " . $employee->first_name . " " . $employee->last_name . "\n";
    } else {
        echo "  -> No employee record\n";
    }
    echo "\n";
}