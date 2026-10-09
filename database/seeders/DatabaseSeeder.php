<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Appointment;
use App\Models\ServiceRecord;
use App\Models\Technician;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create()->each(function ($user) {
            Client::factory()
                ->for($user)
                ->has(Vehicle::factory(2)
                    ->has(Appointment::factory(4)
                        ->for($user)
                        ->has(ServiceRecord::factory(2)
                            ->for(Technician::factory()
                                ->for(User::factory()->state([
                                    'role' => 'technician',
                                ]))
                            )
                        )
                    )
                )
                ->create();
        });
    }
}
