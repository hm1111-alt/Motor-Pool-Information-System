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
        Schema::table('drivers', function (Blueprint $table) {
            // Rename firsts_name to first_name
            $table->renameColumn('firsts_name', 'first_name');
            
            // Remove duplicate columns that exist in users table
            $table->dropColumn(['contact_num', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Restore the dropped columns
            $table->string('contact_num')->nullable();
            $table->string('email')->nullable();
            
            // Rename first_name back to firsts_name
            $table->renameColumn('first_name', 'firsts_name');
        });
    }
};
