<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Technician;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{client: Client, vehicle: Vehicle, technician: Technician, appointment: Appointment}
     */
    private function makeFixtures(): array
    {
        $owner = User::factory()->state(['role' => 'client'])->create();
        $client = Client::factory()->for($owner)->create();
        $vehicle = Vehicle::factory()->for($client, 'client')->create();

        $technicianUser = User::factory()->state(['role' => 'technician'])->create();
        $technician = Technician::factory()->for($technicianUser)->create();

        $appointment = Appointment::factory()->for($vehicle)->for($owner)->for($technician)->create();

        return compact('client', 'vehicle', 'technician', 'appointment');
    }

    public function test_admin_can_update_an_appointment(): void
    {
        $fixtures = $this->makeFixtures();
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $response = $this->actingAs($admin)->put(route('appointments.update', $fixtures['appointment']), [
            'client_id' => $fixtures['client']->id,
            'vehicle_id' => $fixtures['vehicle']->id,
            'technician_id' => $fixtures['technician']->id,
            'date' => '2026-11-01',
            'time' => '10:00',
        ]);

        $response->assertRedirect();
        $this->assertSame($fixtures['vehicle']->id, $fixtures['appointment']->fresh()->vehicle_id);
    }

    public function test_appointment_update_requires_vehicle(): void
    {
        $fixtures = $this->makeFixtures();
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $response = $this->actingAs($admin)->put(route('appointments.update', $fixtures['appointment']), [
            'client_id' => $fixtures['client']->id,
            'technician_id' => $fixtures['technician']->id,
            'date' => '2026-11-01',
            'time' => '10:00',
        ]);

        $response->assertSessionHasErrors('vehicle_id');
    }
}
