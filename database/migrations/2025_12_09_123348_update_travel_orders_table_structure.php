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
        Schema::table('travel_orders', function (Blueprint $table) {
            // Drop existing columns that don't match our requirements
            $table->dropColumn([
                'departure_time',
                'arrival_time',
                'divisionhead_approved',
                'divisionhead_approved_at',
                'president_approved',
                'president_approved_at'
            ]);
            
            // Add new columns for our approval workflow
            $table->text('head_remarks')->nullable();
            $table->text('vp_remarks')->nullable();
            
            // Update status column to use our allowed values
            $table->string('status')->default('pending')->change(); // pending, approved, cancelled
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'head_remarks',
                'vp_remarks'
            ]);
            
            // Add back old columns
            $table->time('departure_time')->nullable();
            $table->time('arrival_time')->nullable();
            $table->boolean('divisionhead_approved')->nullable();
            $table->dateTime('divisionhead_approved_at')->nullable();
            $table->boolean('president_approved')->nullable();
            $table->dateTime('president_approved_at')->nullable();
            
            // Revert status column
            $table->enum('status', ['Pending', 'Approved', 'Cancelled'])->nullable()->change();
        });
    }
};
