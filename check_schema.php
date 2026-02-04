<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

echo "Checking lib_units columns:\n";
$columns = \Illuminate\Support\Facades\Schema::getColumnListing('lib_units');
print_r($columns);

echo "\nChecking lib_divisions columns:\n";
$columns = \Illuminate\Support\Facades\Schema::getColumnListing('lib_divisions');
print_r($columns);
