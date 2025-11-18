<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key constraints
        Schema::disableForeignKeyConstraints();
        
        // Clear existing offices
        DB::table('offices')->truncate();
        
        // Insert sample offices
        $offices = [
            [
                'office_program' => 'Office of the University President',
                'office_name' => 'Office of the University President',
                'office_abbr' => 'OUP',
                'officer_code' => 'OUP',
                'office_isactive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'office_program' => 'General Administration Program',
                'office_name' => 'Office of the Vice President for Administration',
                'office_abbr' => 'OVPAD',
                'officer_code' => 'ADM',
                'office_isactive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'office_program' => 'Academic Affairs Program',
                'office_name' => 'Office of the Vice President for Academic Affairs',
                'office_abbr' => 'OVPAA',
                'officer_code' => 'ACA',
                'office_isactive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'office_program' => 'Research and Development Program',
                'office_name' => 'Office of the Vice President for Research and Development',
                'office_abbr' => 'OVPRED',
                'officer_code' => 'RND',
                'office_isactive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'office_program' => 'Extension and Community Relations Program',
                'office_name' => 'Office of the Vice President for Extension and Community Relations',
                'office_abbr' => 'OVPExCR',
                'officer_code' => 'EXT',
                'office_isactive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('offices')->insert($offices);
        
        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();
    }
}