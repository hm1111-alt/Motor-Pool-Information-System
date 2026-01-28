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
            $table->string('first_name')->nullable();
            $table->string('middle_initial', 10)->nullable();
            $table->string('last_name')->nullable();
            $table->string('ext_name', 50)->nullable();
            $table->string('sex', 20)->nullable();
            $table->string('prefix', 10)->nullable();
            $table->string('contact_number', 50)->nullable();
            $table->string('position_name')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('subunit_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Add foreign key constraints
            $table->foreign('class_id')->references('id')->on('class')->onDelete('set null');
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('set null');
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('set null');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
            $table->foreign('subunit_id')->references('id')->on('subunits')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropForeign(['office_id']);
            $table->dropForeign(['division_id']);
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['subunit_id']);
            $table->dropForeign(['user_id']);
            
            $table->dropColumn([
                'first_name',
                'middle_initial',
                'last_name',
                'ext_name',
                'sex',
                'prefix',
                'contact_number',
                'position_name',
                'class_id',
                'office_id',
                'division_id',
                'unit_id',
                'subunit_id',
                'user_id'
            ]);
        });
    }
};