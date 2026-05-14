<?php

namespace Tests\Feature;

use App\Enums\ApprovalStatus;
use App\Models\Property;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_guests_can_view_approved_properties(): void
    {
        $property = Property::factory()->create();

        $this->get(route('properties.show', $property))->assertOk();
    }

    public function test_buyers_cannot_create_properties(): void
    {
        $user = User::factory()->buyer()->create();

        $this->actingAs($user)
            ->get(route('properties.create'))
            ->assertForbidden();
    }

    public function test_agents_can_create_properties(): void
    {
        $agent = User::factory()->agent()->create();

        $this->actingAs($agent)
            ->post(route('properties.store'), [
                'title'     => 'Test villa',
                'type'      => 'house',
                'status'    => 'for_sale',
                'price'     => 1500000,
                'address'   => '12 Test Lane',
                'city'      => 'Bengaluru',
                'description' => 'A beautiful test home.',
            ])->assertRedirect();

        $this->assertDatabaseHas('properties', [
            'title'    => 'Test villa',
            'owner_id' => $agent->id,
            'approval_status' => ApprovalStatus::Submitted->value,
        ]);
    }

    public function test_owner_can_update_own_property(): void
    {
        $agent = User::factory()->agent()->create();
        $property = Property::factory()->create(['owner_id' => $agent->id]);

        $this->actingAs($agent)
            ->put(route('properties.update', $property), array_merge($property->toArray(), [
                'title' => 'Updated title',
                'type'   => $property->type->value,
                'status' => $property->status->value,
                'price'  => 9000000,
                'address' => $property->address,
                'city'   => $property->city,
            ]))->assertRedirect();

        $this->assertDatabaseHas('properties', [
            'id'    => $property->id,
            'title' => 'Updated title',
        ]);
    }

    public function test_others_cannot_update_someone_elses_property(): void
    {
        $owner = User::factory()->agent()->create();
        $intruder = User::factory()->agent()->create();
        $property = Property::factory()->create(['owner_id' => $owner->id]);

        $this->actingAs($intruder)
            ->put(route('properties.update', $property), ['title' => 'Hacked'])
            ->assertForbidden();
    }
}
