<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Property $property): bool
    {
        if ($property->approval_status?->isVisibleOnSite()) {
            return true;
        }

        if (! $user) return false;

        return $user->isAdmin() || $property->owner_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAgent() || $user->isAdmin();
    }

    public function update(User $user, Property $property): bool
    {
        return $user->isAdmin() || $property->owner_id === $user->id;
    }

    public function delete(User $user, Property $property): bool
    {
        return $user->isAdmin() || $property->owner_id === $user->id;
    }

    public function approve(User $user, Property $property): bool
    {
        return $user->isAdmin();
    }
}
