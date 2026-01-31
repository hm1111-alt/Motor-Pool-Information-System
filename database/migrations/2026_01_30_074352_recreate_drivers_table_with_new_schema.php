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
            // Drop columns that don't match the new schema
            $table->dropColumn(['ext_name', 'sex', 'prefix']);
            
            // Rename first_name to firsts_name to match your schema
            $table->renameColumn('first_name', 'firsts_name');
            
            // Add missing columns to match your exact schema
            $table->string('contact_num')->after('full_name2');
            $table->string('email')->after('contact_num');
            $table->string('password')->after('email');
            $table->text('address')->after('password');
            $table->string('position')->after('address');
            $table->string('official_station')->after('position');
            $table->string('availability_status')->default('Available')->after('official_station');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Drop the new columns
            $table->dropColumn([
                'contact_num',
                'email',
                'password',
                'address',
                'position',
                'official_station',
                'availability_status'
            ]);
            
            // Rename firsts_name back to first_name
            $table->renameColumn('firsts_name', 'first_name');
            
            // Recreate the dropped columns
            $table->string('ext_name')->nullable()->after('last_name');
            $table->string('sex')->nullable()->after('ext_name');
            $table->string('prefix')->nullable()->after('sex');
        });
    }
};