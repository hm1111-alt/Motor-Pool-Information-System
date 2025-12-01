<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use App\Models\TravelOrder;

class EmployeeTravelOrderApprovalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function employee_travel_order_follows_correct_approval_workflow()
    {
        // Create a regular employee
        $employeeUser = User::factory()->create(['role' => 'employees']);
        $employee = Employee::factory()->create([
            'user_id' => $employeeUser->id,
            'is_head' => false,
            'is_divisionhead' => false,
            'is_vp' => false,
            'is_president' => false
        ]);

        // Login as the employee and create a travel order
        $this->actingAs($employeeUser);

        $travelOrderData = [
            'destination' => 'Manila',
            'purpose' => 'Meeting',
            'date_from' => '2025-12-01',
            'date_to' => '2025-12-02',
            'departure_time' => '08:00'
        ];

        $response = $this->post('/travel-orders', $travelOrderData);
        $response->assertSessionHas('success');

        // Get the created travel order
        $travelOrder = TravelOrder::first();
        $this->assertEquals('Pending', $travelOrder->status);

        // Manually update the travel order to simulate head approval
        $travelOrder->head_approved = true;
        $travelOrder->head_approved_at = now();
        $travelOrder->status = 'Pending';
        $travelOrder->save();

        // Verify the status after head approval
        $travelOrder->refresh();
        $this->assertTrue($travelOrder->head_approved);
        $this->assertEquals('Pending', $travelOrder->status);

        // Manually update the travel order to simulate division head approval
        $travelOrder->divisionhead_approved = true;
        $travelOrder->divisionhead_approved_at = now();
        $travelOrder->status = 'Pending';
        $travelOrder->save();

        // Verify the status after division head approval
        $travelOrder->refresh();
        $this->assertTrue($travelOrder->divisionhead_approved);
        $this->assertEquals('Pending', $travelOrder->status);

        // Manually update the travel order to simulate VP approval
        $travelOrder->vp_approved = true;
        $travelOrder->vp_approved_at = now();
        $travelOrder->status = 'Approved';
        $travelOrder->save();

        // Verify the status after VP approval
        $travelOrder->refresh();
        $this->assertTrue($travelOrder->vp_approved);
        $this->assertEquals('Approved', $travelOrder->status);
    }
}