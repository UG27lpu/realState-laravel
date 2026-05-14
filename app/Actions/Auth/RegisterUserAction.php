<?php

namespace App\Actions\Auth;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    /**
     * Create a new user account and attach the requested role. Agent accounts
     * pick up an agency name; everyone else lands as a normal user.
     */
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'        => $data['name'],
                'email'       => strtolower($data['email']),
                'phone'       => $data['phone'] ?? null,
                'password'    => Hash::make($data['password']),
                'agency_name' => $data['role'] === Role::Agent->value ? ($data['agency_name'] ?? null) : null,
                'is_active'   => true,
            ]);

            $user->assignRole($data['role']);

            return $user;
        });
    }
}
