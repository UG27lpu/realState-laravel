<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'phone'             => fake()->optional()->phoneNumber(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'is_active'         => true,
            'remember_token'    => Str::random(10),
        ];
    }

    public function agent(): static
    {
        return $this->state(fn () => [
            'agency_name' => fake()->company().' Realty',
        ])->afterCreating(fn (User $u) => $u->assignRole('agent'));
    }

    public function admin(): static
    {
        return $this->afterCreating(fn (User $u) => $u->assignRole('admin'));
    }

    public function buyer(): static
    {
        return $this->afterCreating(fn (User $u) => $u->assignRole('user'));
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}
