<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $agents = User::role('agent')->get();

        if ($agents->isEmpty()) {
            $agents = User::factory()->count(2)->agent()->create();
        }

        foreach ($agents as $agent) {
            Property::factory()
                ->count(10)
                ->state(['owner_id' => $agent->id])
                ->create();

            Property::factory()
                ->count(2)
                ->featured()
                ->state(['owner_id' => $agent->id])
                ->create();

            Property::factory()
                ->count(2)
                ->pending()
                ->state(['owner_id' => $agent->id])
                ->create();
        }
    }
}
