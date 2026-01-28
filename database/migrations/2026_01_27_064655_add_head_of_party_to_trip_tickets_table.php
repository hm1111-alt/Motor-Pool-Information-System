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
            if (!Schema::hasColumn('trip_tickets', 'head_of_party')) {
                $table->string('head_of_party')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_tickets', function (Blueprint $table) {
            if (Schema::hasColumn('trip_tickets', 'head_of_party')) {
                $table->dropColumn('head_of_party');
            }
        });
    }
};
