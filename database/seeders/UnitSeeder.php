<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key constraints
        Schema::disableForeignKeyConstraints();
        
        // Clear existing units
        DB::table('units')->truncate();
        
        // Get all divisions
        $divisions = DB::table('divisions')->get();
        
        // Insert sample units for each division
        $units = [];
        
        foreach ($divisions as $division) {
            // Create 2-3 units for each division
            $unitCount = rand(2, 3);
            
            for ($i = 1; $i <= $unitCount; $i++) {
                $units[] = [
                    'division_id' => $division->id,
                    'unit_name' => $division->division_code . ' Unit ' . $i,
                    'unit_code' => $division->division_code . '-UNIT' . $i,
                    'unit_isactive' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        DB::table('units')->insert($units);
        
        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();
    }
}