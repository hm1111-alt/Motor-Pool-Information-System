<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       

        // Class
        Schema::create('class', function (Blueprint $table) {
            $table->id();
            $table->string('class_name', 100)->nullable();
            $table->timestamps();
        });

        // Offices
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('office_program')->nullable();
            $table->string('office_name')->nullable();
            $table->string('office_abbr', 100)->nullable();
            $table->string('officer_code', 50)->nullable();
            $table->boolean('office_isactive')->nullable();
            $table->timestamps();
        });

        // Divisions
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('division_name')->nullable();
            $table->string('division_abbr', 100)->nullable();
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->string('division_code', 50)->nullable();
            $table->boolean('division_isactive')->nullable();
            $table->timestamps();
        });

        // Units
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_name')->nullable();
            $table->string('unit_abbr', 100)->nullable();
            $table->string('unit_code', 50)->nullable();
            $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete();
            $table->boolean('unit_isactive')->nullable();
            $table->timestamps();
        });

        // Subunits
        Schema::create('subunits', function (Blueprint $table) {
            $table->id();
            $table->string('subunit_name')->nullable();
            $table->string('subunit_abbr', 100)->nullable();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->boolean('subunit_isactive')->nullable();
            $table->timestamps();
        });

        // Status
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->string('status_name')->nullable();
            $table->boolean('status_isactive')->nullable();
            $table->boolean('status_is_end')->nullable();
            $table->boolean('status_retirement')->nullable();
            $table->boolean('status_lock')->nullable();
            $table->boolean('status_is_service')->nullable();
            $table->boolean('for_plantilla')->nullable();
            $table->timestamps();
        });

        // Employees
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_initial', 10)->nullable();
            $table->string('ext_name', 50)->nullable();
            $table->string('full_name')->nullable();
            $table->string('full_name2')->nullable();
            $table->string('sex', 20)->nullable();
            $table->string('prefix', 10)->nullable();
            $table->integer('emp_status')->nullable();
            $table->foreignId('class_id')->nullable()->constrained('class')->nullOnDelete();
            $table->string('position_name')->nullable();
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('subunit_id')->nullable()->constrained('subunits')->nullOnDelete();
            $table->boolean('is_divisionhead')->nullable();
            $table->boolean('is_vp')->nullable();
            $table->timestamps();
        });

        // Drivers
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_initial', 10)->nullable();
            $table->string('ext_name', 50)->nullable();
            $table->string('full_name')->nullable();
            $table->string('full_name2')->nullable();
            $table->string('sex', 20)->nullable();
            $table->string('contact_number', 50)->nullable();
            $table->string('position')->nullable();
            $table->string('official_station')->nullable();
            $table->string('availability_status', 100)->nullable();
            $table->timestamps();
        });

        // Vehicle
        Schema::create('vehicle', function (Blueprint $table) {
            $table->id();
            $table->string('picture')->nullable();
            $table->string('plate_number', 50)->nullable();
            $table->string('model')->nullable();
            $table->string('type')->nullable();
            $table->integer('seating_capacity')->nullable();
            $table->integer('mileage')->nullable();
            $table->enum('status', ['Available', 'Not Available', 'Active', 'Under Maintenance'])->nullable();
            $table->timestamps();
        });

        // Travel Orders
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('destination')->nullable();
            $table->text('purpose')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->time('departure_time')->nullable();
            $table->time('arrival_time')->nullable();
            $table->enum('status', ['Approved', 'Not yet Approved', 'Cancelled'])->nullable();
            $table->boolean('divisionhead_approved')->nullable();
            $table->dateTime('divisionhead_approved_at')->nullable();
            $table->boolean('vp_approved')->nullable();
            $table->dateTime('vp_approved_at')->nullable();
            $table->boolean('divisionhead_declined')->nullable();
            $table->dateTime('divisionhead_declined_at')->nullable();
            $table->boolean('vp_declined')->nullable();
            $table->dateTime('vp_declined_at')->nullable();
            $table->timestamps();
        });

        // Itineraries
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_orders_id')->nullable()->constrained('travel_orders')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicle')->nullOnDelete();
            $table->text('purpose')->nullable();
            $table->string('status', 100)->nullable();
            $table->timestamps();
        });

        // Trip Tickets
        Schema::create('trip_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')->nullable()->constrained('itineraries')->nullOnDelete();
            $table->foreignId('travel_order_id')->nullable()->constrained('travel_orders')->nullOnDelete();
            $table->boolean('vp_approved')->nullable();
            $table->dateTime('vp_approved_at')->nullable();
            $table->boolean('vp_declined')->nullable();
            $table->dateTime('vp_declined_at')->nullable();
            $table->enum('status', ['Approved', 'Not yet Approved', 'Cancelled'])->nullable();
            $table->string('trip_document')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_tickets');
        Schema::dropIfExists('itineraries');
        Schema::dropIfExists('travel_orders');
        Schema::dropIfExists('vehicle');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('status');
        Schema::dropIfExists('subunits');
        Schema::dropIfExists('units');
        Schema::dropIfExists('divisions');
        Schema::dropIfExists('offices');
        Schema::dropIfExists('class');
        Schema::dropIfExists('users');
    }
};
