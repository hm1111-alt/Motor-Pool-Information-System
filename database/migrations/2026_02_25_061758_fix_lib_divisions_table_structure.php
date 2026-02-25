<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, make sure the id_division column is a primary key and auto-incrementing
        DB::statement('ALTER TABLE lib_divisions MODIFY id_division BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to non-primary key and non-auto-incrementing
        DB::statement('ALTER TABLE lib_divisions DROP PRIMARY KEY, MODIFY id_division BIGINT UNSIGNED NOT NULL');
    }
};