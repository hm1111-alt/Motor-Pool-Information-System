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
        Schema::table('lib_divisions', function (Blueprint $table) {
            // Make id_division auto-incrementing
            $table->unsignedBigInteger('id_division', true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lib_divisions', function (Blueprint $table) {
            // Revert to non-auto-incrementing
            $table->unsignedBigInteger('id_division', false)->change();
        });
    }
};
