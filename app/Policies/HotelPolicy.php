<?php

namespace App\Policies;

use App\Enum\UserRoleType;
use App\Models\Hotel;
use App\Models\User;

class HotelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            UserRoleType::HOTEL_CLERK->value,
            UserRoleType::HOTEL_MANAGER->value,
            UserRoleType::SUPER_ADMIN->value,
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Hotel $hotel): bool
    {
        return $user->userHotels
            ->where('hotel_id', $hotel->id)
            ->isNotEmpty() || $user->hasAllRoles([UserRoleType::SUPER_ADMIN->value]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            UserRoleType::HOTEL_MANAGER->value,
            UserRoleType::SUPER_ADMIN->value,
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Hotel $hotel): bool
    {
        return $user->userHotels
            ->where('hotel_id', $hotel->id)
            ->isNotEmpty() || $user->hasAllRoles([UserRoleType::SUPER_ADMIN->value]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Hotel $hotel): bool
    {
        return $user->hasAnyRole([UserRoleType::SUPER_ADMIN->value]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Hotel $hotel): bool
    {
        return $user->hasAnyRole([UserRoleType::SUPER_ADMIN->value]);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Hotel $hotel): bool
    {
        return $user->hasAnyRole([UserRoleType::SUPER_ADMIN->value]);
    }
}
