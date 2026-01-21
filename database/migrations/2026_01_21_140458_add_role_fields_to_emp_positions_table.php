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
            $table->boolean('is_unit_head')->default(false);
            $table->boolean('is_division_head')->default(false);
            $table->boolean('is_vp')->default(false);
            $table->boolean('is_president')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_positions', function (Blueprint $table) {
            $table->dropColumn(['is_unit_head', 'is_division_head', 'is_vp', 'is_president']);
        });
    }
};
