<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateDriversWithEmpIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all existing drivers
        $drivers = DB::table('drivers')->get();
        
        // Get all employees
        $employees = DB::table('employees')->get();
        
        foreach ($drivers as $driver) {
            // Find matching employee by name (first and last name)
            $matchingEmployee = $employees->first(function ($employee) use ($driver) {
                // Try to match by full name if available
                if (!empty($driver->full_name) && !empty($employee->full_name)) {
                    return $driver->full_name === $employee->full_name;
                }
                // Fallback to matching first and last name
                return !empty($driver->first_name) && !empty($driver->last_name) && 
                       $driver->first_name === $employee->first_name && 
                       $driver->last_name === $employee->last_name;
            });
            
            if ($matchingEmployee) {
                // Update the driver with emp_id
                DB::table('drivers')
                    ->where('id', $driver->id)
                    ->update([
                        'emp_id' => $matchingEmployee->id,
                        'availability_status' => $driver->availability_status ?? 'Active'
                    ]);
            } else {
                // If no matching employee found, create a basic link with default status
                DB::table('drivers')
                    ->where('id', $driver->id)
                    ->update([
                        'availability_status' => $driver->availability_status ?? 'Active'
                    ]);
            }
        }
    }
}