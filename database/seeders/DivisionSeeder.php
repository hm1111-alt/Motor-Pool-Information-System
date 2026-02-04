<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key constraints
        Schema::disableForeignKeyConstraints();
        
        // Clear existing divisions
        DB::table('divisions')->truncate();
        
        // Get all offices
        $offices = DB::table('offices')->get();
        
        // Insert sample divisions for each office
        $divisions = [];
        
        foreach ($offices as $office) {
            // Create 2-3 divisions for each office
            $divisionCount = rand(2, 3);
            
            for ($i = 1; $i <= $divisionCount; $i++) {
                $divisions[] = [
                    'office_id' => $office->id,
                    'division_name' => $office->office_abbr . ' Division ' . $i,
                    'division_code' => $office->office_abbr . '-DIV' . $i,
                    'division_isactive' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        DB::table('divisions')->insert($divisions);
        
        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();
    }
}