<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['admin', 'Sona Patel', 'admin@estatify.test', null],
            ['agent', 'Vikram Joshi', 'agent@estatify.test', 'Joshi Realty'],
            ['agent', 'Priya Mehta', 'priya@estatify.test', 'Mehta & Co. Estates'],
            ['user', 'Rohan Sharma', 'user@estatify.test', null],
            ['user', 'Anita Kapoor', 'anita@estatify.test', null],
        ];

        foreach ($accounts as [$role, $name, $email, $agency]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'        => $name,
                    'password'    => Hash::make('password'),
                    'agency_name' => $agency,
                    'is_active'   => true,
                    'email_verified_at' => now(),
                ]
            );

            if (! $user->hasRole($role)) {
                $user->syncRoles([$role]);
            }
        }
    }
}
