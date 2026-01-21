<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('emp_positions', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('subunit_id');
        });
        
        // Set the first position for each employee as primary
        \DB::statement("UPDATE emp_positions SET is_primary = 1 WHERE id IN (
            SELECT * FROM (
                SELECT MIN(id) FROM emp_positions GROUP BY employee_id
            ) AS t
        )");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_positions', function (Blueprint $table) {
            $table->dropColumn('is_primary');
        });
    }
};
