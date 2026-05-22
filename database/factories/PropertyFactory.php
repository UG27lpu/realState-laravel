<?php

namespace Database\Factories;

use App\Enums\ApprovalStatus;
use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Models\Property;
use App\Models\PropertyImage;
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

    public function configure(): static
    {
        return $this->afterCreating(function (Property $property) {
            $pool = $this->imagePool($property->type);
            $urls = collect($pool)->shuffle()->take(fake()->numberBetween(3, 5));

            foreach ($urls as $idx => $url) {
                PropertyImage::create([
                    'property_id' => $property->id,
                    'path'        => $url,
                    'caption'     => null,
                    'is_cover'    => $idx === 0,
                    'sort_order'  => $idx,
                ]);
            }
        });
    }

    private function imagePool(PropertyType $type): array
    {
        return match ($type) {
            PropertyType::House => [
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c',
                'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9',
                'https://images.unsplash.com/photo-1570129477492-45c003edd2be',
                'https://images.unsplash.com/photo-1612637968894-660373e23b03',
                'https://images.unsplash.com/photo-1484154218962-a197022b5858',
                'https://images.unsplash.com/photo-1556912173-3bb406ef7e8d',
                'https://images.unsplash.com/photo-1507089947368-19c1da9775ae',
                'https://images.unsplash.com/photo-1480074568708-e7b720bb3f09',
            ],
            PropertyType::Apartment => [
                'https://images.unsplash.com/photo-1493809842364-78817add7ffb',
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688',
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2',
                'https://images.unsplash.com/photo-1554995207-c18c203602cb',
                'https://images.unsplash.com/photo-1507089947368-19c1da9775ae',
                'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92',
                'https://images.unsplash.com/photo-1524758631624-e2822e304c36',
            ],
            PropertyType::Commercial => [
                'https://images.unsplash.com/photo-1497366216548-37526070297c',
                'https://images.unsplash.com/photo-1497366811353-6870744d04b2',
                'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40',
                'https://images.unsplash.com/photo-1524758631624-e2822e304c36',
                'https://images.unsplash.com/photo-1581922814484-0b48460b7010',
            ],
            PropertyType::Land => [
                'https://images.unsplash.com/photo-1500382017468-9049fed747ef',
                'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b',
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e',
                'https://images.unsplash.com/photo-1504701954957-2010ec3bcec1',
            ],
        };
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
