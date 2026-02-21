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
        Schema::table('trip_tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('trip_tickets', 'ticket_number')) {
                $table->string('ticket_number')->unique()->nullable(); // Make it unique and nullable initially
            }
        });
        
        // Generate ticket numbers for existing records
        $tripTickets = \DB::table('trip_tickets')->whereNull('ticket_number')->get();
        foreach ($tripTickets as $tripTicket) {
            $ticketNumber = 'TT-' . str_pad($tripTicket->id, 6, '0', STR_PAD_LEFT);
            \DB::table('trip_tickets')
                ->where('id', $tripTicket->id)
                ->update(['ticket_number' => $ticketNumber]);
        }
        
        // Make ticket_number required after populating existing records
        \DB::statement('ALTER TABLE trip_tickets MODIFY ticket_number VARCHAR(255) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_tickets', function (Blueprint $table) {
            $table->dropColumn('ticket_number');
        });
    }
};
