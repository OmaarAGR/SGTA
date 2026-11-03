<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    private function makeClient(): Client
    {
        return Client::factory()->for(User::factory()->state(['role' => 'client']))->create();
    }

    public function test_admin_can_create_a_vehicle(): void
    {
        $client = $this->makeClient();
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $response = $this->actingAs($admin)->post(route('vehicles.store'), [
            'license_plate' => 'ABC123',
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2020,
            'client_id' => $client->id,
        ]);

        $vehicle = Vehicle::where('license_plate', 'ABC123')->first();
        $this->assertNotNull($vehicle);
        $response->assertRedirect(route('vehicles.show', $vehicle));
    }

    public function test_vehicle_creation_requires_license_plate(): void
    {
        $client = $this->makeClient();
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $response = $this->actingAs($admin)->post(route('vehicles.store'), [
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2020,
            'client_id' => $client->id,
        ]);

        $response->assertSessionHasErrors('license_plate');
        $this->assertDatabaseCount('vehicles', 0);
    }

    public function test_admin_can_update_a_vehicle(): void
    {
        $client = $this->makeClient();
        $vehicle = Vehicle::factory()->for($client, 'client')->create();
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $response = $this->actingAs($admin)->put(route('vehicles.update', $vehicle), [
            'license_plate' => $vehicle->license_plate,
            'make' => 'Updated Make',
            'model' => $vehicle->model,
            'year' => $vehicle->year,
            'client_id' => $client->id,
        ]);

        $response->assertRedirect(route('vehicles.show', $vehicle));
        $this->assertSame('Updated Make', $vehicle->fresh()->make);
    }
}
