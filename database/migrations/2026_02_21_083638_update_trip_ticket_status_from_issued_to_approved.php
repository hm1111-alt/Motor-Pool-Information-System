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
        // Update all trip tickets with 'Issued' status to 'Approved'
        \DB::table('trip_tickets')
            ->where('status', 'Issued')
            ->update(['status' => 'Approved']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'Approved' status back to 'Issued' for affected records
        \DB::table('trip_tickets')
            ->where('status', 'Approved')
            ->update(['status' => 'Issued']);
    }
};
