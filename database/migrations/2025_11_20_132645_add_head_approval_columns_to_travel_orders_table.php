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
            $table->boolean('head_approved')->nullable()->after('vp_declined_at');
            $table->boolean('head_disapproved')->nullable()->after('head_approved');
            $table->timestamp('head_approved_at')->nullable()->after('head_disapproved');
            $table->timestamp('head_disapproved_at')->nullable()->after('head_approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->dropColumn(['head_approved', 'head_disapproved', 'head_approved_at', 'head_disapproved_at']);
        });
    }
};