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
        Schema::table('itineraries', function (Blueprint $table) {
            // Add destination and purpose columns if they don't exist
            if (!Schema::hasColumn('itineraries', 'destination')) {
                $table->string('destination')->nullable()->after('date_to');
            }
            if (!Schema::hasColumn('itineraries', 'purpose')) {
                $table->text('purpose')->nullable()->after('destination');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itineraries', function (Blueprint $table) {
            $table->dropColumn(['destination', 'purpose']);
        });
    }
};