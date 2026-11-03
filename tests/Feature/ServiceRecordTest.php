<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\ServiceRecord;
use App\Models\Technician;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRecordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{appointment: Appointment, technician: Technician}
     */
    private function makeAppointmentAndTechnician(): array
    {
        $owner = User::factory()->state(['role' => 'client'])->create();
        $client = Client::factory()->for($owner)->create();
        $vehicle = Vehicle::factory()->for($client, 'client')->create();

        $technicianUser = User::factory()->state(['role' => 'technician'])->create();
        $technician = Technician::factory()->for($technicianUser)->create();

        $appointment = Appointment::factory()->for($vehicle)->for($owner)->for($technician)->create();

        return compact('appointment', 'technician');
    }

    public function test_admin_can_create_a_service_record(): void
    {
        ['appointment' => $appointment, 'technician' => $technician] = $this->makeAppointmentAndTechnician();
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $response = $this->actingAs($admin)->post(route('service_records.store'), [
            'appointment_id' => $appointment->id,
            'start_datetime' => now()->toDateTimeString(),
            'technician_id' => $technician->id,
        ]);

        $this->assertDatabaseCount('service_records', 1);
        $serviceRecord = ServiceRecord::first();
        $response->assertRedirect(route('service_records.show', $serviceRecord));
    }

    public function test_service_record_creation_requires_technician(): void
    {
        ['appointment' => $appointment] = $this->makeAppointmentAndTechnician();
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $response = $this->actingAs($admin)->post(route('service_records.store'), [
            'appointment_id' => $appointment->id,
            'start_datetime' => now()->toDateTimeString(),
        ]);

        $response->assertSessionHasErrors('technician_id');
        $this->assertDatabaseCount('service_records', 0);
    }

    public function test_admin_can_update_a_service_record(): void
    {
        ['appointment' => $appointment, 'technician' => $technician] = $this->makeAppointmentAndTechnician();
        $serviceRecord = ServiceRecord::factory()->for($appointment)->for($technician)->create();
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $response = $this->actingAs($admin)->put(route('service_records.update', $serviceRecord), [
            'appointment_id' => $appointment->id,
            'start_datetime' => now()->toDateTimeString(),
            'technician_id' => $technician->id,
            'service_performed' => 'Oil change',
        ]);

        $response->assertRedirect(route('service_records.show', $serviceRecord));
        $this->assertSame('Oil change', $serviceRecord->fresh()->service_performed);
    }
}
