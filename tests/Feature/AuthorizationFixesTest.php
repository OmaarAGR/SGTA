<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\ServiceRecord;
use App\Models\Technician;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AuthorizationFixesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{owner: User, client: Client, vehicle: Vehicle, technician: Technician, appointment: Appointment, serviceRecord: ServiceRecord}
     */
    private function makeFixtures(): array
    {
        $owner = User::factory()->state(['role' => 'client'])->create();
        $client = Client::factory()->for($owner)->create();
        $vehicle = Vehicle::factory()->for($client, 'client')->create();

        $technicianUser = User::factory()->state(['role' => 'technician'])->create();
        $technician = Technician::factory()->for($technicianUser)->create();

        $appointment = Appointment::factory()->for($vehicle)->for($owner)->for($technician)->create();
        $serviceRecord = ServiceRecord::factory()->for($appointment)->for($technician)->create();

        return compact('owner', 'client', 'vehicle', 'technician', 'appointment', 'serviceRecord');
    }

    public function test_user_without_permission_cannot_store_a_vehicle(): void
    {
        $this->makeFixtures();
        $stranger = User::factory()->state(['role' => 'client'])->create();

        $response = $this->actingAs($stranger)->post(route('vehicles.store'), []);

        $response->assertForbidden();
    }

    public function test_admin_can_reach_vehicle_update_without_being_blocked(): void
    {
        $fixtures = $this->makeFixtures();
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $response = $this->actingAs($admin)->put(route('vehicles.update', $fixtures['vehicle']), []);

        $response->assertRedirect(route('vehicles.show', $fixtures['vehicle']));
    }

    public function test_user_without_permission_cannot_store_a_service_record(): void
    {
        $this->makeFixtures();
        $stranger = User::factory()->state(['role' => 'client'])->create();

        $response = $this->actingAs($stranger)->post(route('service_records.store'), []);

        $response->assertForbidden();
    }

    public function test_admin_can_reach_service_record_update_without_being_blocked(): void
    {
        $fixtures = $this->makeFixtures();
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $response = $this->actingAs($admin)->put(route('service_records.update', $fixtures['serviceRecord']), []);

        $response->assertRedirect(route('service_records.show', $fixtures['serviceRecord']));
    }

    public function test_user_without_permission_cannot_update_an_appointment(): void
    {
        $fixtures = $this->makeFixtures();
        $stranger = User::factory()->state(['role' => 'client'])->create();

        $response = $this->actingAs($stranger)->put(route('appointments.update', $fixtures['appointment']), []);

        $response->assertForbidden();
    }

    public function test_admin_passes_the_authorization_gate_for_the_fixed_actions(): void
    {
        $fixtures = $this->makeFixtures();
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $this->assertTrue(Gate::forUser($admin)->allows('create', Vehicle::class));
        $this->assertTrue(Gate::forUser($admin)->allows('update', $fixtures['vehicle']));
        $this->assertTrue(Gate::forUser($admin)->allows('create', ServiceRecord::class));
        $this->assertTrue(Gate::forUser($admin)->allows('update', $fixtures['serviceRecord']));
        $this->assertTrue(Gate::forUser($admin)->allows('update', $fixtures['appointment']));
    }
}
