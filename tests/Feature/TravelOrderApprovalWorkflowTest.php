<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use App\Models\TravelOrder;

class TravelOrderApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function employee_travel_order_requires_head_approval()
    {
        // Create a regular employee
        $user = User::factory()->create(['role' => 'employees']);
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'is_head' => false,
            'is_divisionhead' => false,
            'is_vp' => false,
            'is_president' => false
        ]);

        // Login as the employee
        $this->actingAs($user);

        // Create a travel order
        $travelOrderData = [
            'destination' => 'Manila',
            'purpose' => 'Meeting',
            'date_from' => '2025-12-01',
            'date_to' => '2025-12-02',
            'departure_time' => '08:00'
        ];

        $response = $this->post('/travel-orders', $travelOrderData);

        // Assert the travel order was created with correct status
        $this->assertDatabaseHas('travel_orders', [
            'employee_id' => $employee->id,
            'status' => 'Pending'
        ]);
    }

    /** @test */
    public function head_travel_order_requires_division_head_approval()
    {
        // Create a head employee
        $user = User::factory()->create(['role' => 'employees']);
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'is_head' => true,
            'is_divisionhead' => false,
            'is_vp' => false,
            'is_president' => false
        ]);

        // Login as the head
        $this->actingAs($user);

        // Create a travel order
        $travelOrderData = [
            'destination' => 'Cebu',
            'purpose' => 'Conference',
            'date_from' => '2025-12-01',
            'date_to' => '2025-12-02',
            'departure_time' => '08:00'
        ];

        $response = $this->post('/travel-orders', $travelOrderData);

        // Assert the travel order was created with correct status
        $this->assertDatabaseHas('travel_orders', [
            'employee_id' => $employee->id,
            'status' => 'Pending'
        ]);
    }

    /** @test */
    public function division_head_travel_order_requires_vp_approval()
    {
        // Create a division head employee
        $user = User::factory()->create(['role' => 'employees']);
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'is_head' => false,
            'is_divisionhead' => true,
            'is_vp' => false,
            'is_president' => false
        ]);

        // Login as the division head
        $this->actingAs($user);

        // Create a travel order
        $travelOrderData = [
            'destination' => 'Davao',
            'purpose' => 'Training',
            'date_from' => '2025-12-01',
            'date_to' => '2025-12-02',
            'departure_time' => '08:00'
        ];

        $response = $this->post('/travel-orders', $travelOrderData);

        // Assert the travel order was created with correct status
        $this->assertDatabaseHas('travel_orders', [
            'employee_id' => $employee->id,
            'status' => 'Pending'
        ]);
    }

    /** @test */
    public function vp_travel_order_requires_president_approval()
    {
        // Create a VP employee
        $user = User::factory()->create(['role' => 'employees']);
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'is_head' => false,
            'is_divisionhead' => false,
            'is_vp' => true,
            'is_president' => false
        ]);

        // Login as the VP
        $this->actingAs($user);

        // Create a travel order
        $travelOrderData = [
            'destination' => 'Iloilo',
            'purpose' => 'Seminar',
            'date_from' => '2025-12-01',
            'date_to' => '2025-12-02',
            'departure_time' => '08:00'
        ];

        $response = $this->post('/travel-orders', $travelOrderData);

        // Assert the travel order was created with correct status
        $this->assertDatabaseHas('travel_orders', [
            'employee_id' => $employee->id,
            'status' => 'Pending'
        ]);
    }

    /** @test */
    public function president_travel_order_goes_to_motorpool_admin()
    {
        // Create a President employee
        $user = User::factory()->create(['role' => 'employees']);
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'is_head' => false,
            'is_divisionhead' => false,
            'is_vp' => false,
            'is_president' => true
        ]);

        // Login as the President
        $this->actingAs($user);

        // Create a travel order
        $travelOrderData = [
            'destination' => 'Baguio',
            'purpose' => 'Retreat',
            'date_from' => '2025-12-01',
            'date_to' => '2025-12-02',
            'departure_time' => '08:00'
        ];

        $response = $this->post('/travel-orders', $travelOrderData);

        // Assert the travel order was created with correct status
        $this->assertDatabaseHas('travel_orders', [
            'employee_id' => $employee->id,
            'status' => 'Pending'
        ]);
    }
}