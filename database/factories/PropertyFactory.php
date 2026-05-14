<?php

namespace Database\Factories;

use App\Enums\ApprovalStatus;
use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->randomElement([
            'Sunrise Heights',
            'Lotus Garden Villa',
            'Skyline Residency',
            'Riverside Plot',
            'Heritage Bungalow',
            'Greenfield Apartment',
            'Crystal Cove Penthouse',
            'Cedar Lane Townhouse',
            'Maple Court Studio',
            'Harbor View Loft',
            'Orchid Tower Flat',
            'Stone Bridge Estate',
        ]).' '.fake()->randomElement(['East', 'West', 'North', 'South', 'Central']);

        $type   = fake()->randomElement(PropertyType::cases());
        $status = fake()->randomElement([PropertyStatus::ForSale, PropertyStatus::ForRent]);
        $city   = fake()->randomElement(['Bengaluru', 'Mumbai', 'Pune', 'Hyderabad', 'Delhi NCR', 'Chennai', 'Kolkata', 'Ahmedabad']);
        $area   = match ($type) {
            PropertyType::Land => fake()->numberBetween(800, 12000),
            PropertyType::Commercial => fake()->numberBetween(500, 8000),
            default => fake()->numberBetween(450, 4500),
        };

        return [
            'owner_id'        => User::factory()->agent(),
            'title'           => $title,
            'slug'            => Str::slug($title.' '.fake()->unique()->randomNumber(5)),
            'description'     => fake()->paragraph(5),
            'type'            => $type->value,
            'status'          => $status->value,
            'approval_status' => ApprovalStatus::Approved->value,
            'price'           => fake()->numberBetween(2_500_000, 50_000_000),
            'area'            => $area,
            'area_unit'       => 'sqft',
            'bedrooms'        => $type === PropertyType::Land ? null : fake()->numberBetween(1, 5),
            'bathrooms'       => $type === PropertyType::Land ? null : fake()->numberBetween(1, 4),
            'floors'          => $type === PropertyType::Land ? null : fake()->numberBetween(1, 3),
            'year_built'      => $type === PropertyType::Land ? null : fake()->numberBetween(1995, (int) date('Y')),
            'furnished'       => fake()->boolean(40),
            'parking'         => fake()->boolean(70),
            'address'         => fake()->streetAddress(),
            'city'            => $city,
            'state'           => fake()->randomElement(['Karnataka', 'Maharashtra', 'Telangana', 'Delhi', 'Tamil Nadu', 'Gujarat', 'West Bengal']),
            'pincode'         => (string) fake()->numberBetween(110000, 689999),
            'country'         => 'India',
            'latitude'        => fake()->latitude(8, 35),
            'longitude'       => fake()->longitude(68, 97),
            'survey_number'   => 'SRV-'.fake()->unique()->numerify('####-##'),
            'is_featured'     => fake()->boolean(25),
            'view_count'      => fake()->numberBetween(0, 1500),
            'nearby_facilities' => fake()->randomElements(
                ['School', 'Metro station', 'Hospital', 'Park', 'Supermarket', 'Mall', 'Highway access', 'Airport'],
                fake()->numberBetween(2, 5)
            ),
            'approved_at'     => now()->subDays(fake()->numberBetween(1, 90)),
        ];
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }

    public function pending(): static
    {
        return $this->state(['approval_status' => ApprovalStatus::Submitted->value, 'approved_at' => null]);
    }
}
