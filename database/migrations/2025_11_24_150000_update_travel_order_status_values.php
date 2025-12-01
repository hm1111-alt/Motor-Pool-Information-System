<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing records to use the new status values
        DB::table('travel_orders')->where('status', 'Not yet Approved')->update(['status' => 'Pending']);
        DB::table('travel_orders')->where('status', 'Pending Head Approval')->update(['status' => 'Pending']);
        DB::table('travel_orders')->where('status', 'Pending Division Head Approval')->update(['status' => 'Pending']);
        DB::table('travel_orders')->where('status', 'Pending VP Approval')->update(['status' => 'Pending']);
        DB::table('travel_orders')->where('status', 'Pending President Approval')->update(['status' => 'Pending']);
        DB::table('travel_orders')->where('status', 'For VP Approval')->update(['status' => 'Pending']);
        DB::table('travel_orders')->where('status', 'Pending Motorpool Admin Approval')->update(['status' => 'Pending']);
        
        Schema::table('travel_orders', function (Blueprint $table) {
            // Update the status column to use the new enum values
            $table->enum('status', ['Pending', 'Approved', 'Cancelled'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: We can't easily revert the data changes since we've lost the original detailed status information
        Schema::table('travel_orders', function (Blueprint $table) {
            // Revert to the old enum values - this might not work perfectly due to data loss
            $table->string('status', 255)->nullable()->change();
        });
    }
};