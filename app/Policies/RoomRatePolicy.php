<?php

namespace App\Policies;

use App\Enum\UserRoleType;
use App\Models\RoomRate;
use App\Models\User;

class RoomRatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            UserRoleType::HOTEL_MANAGER->value,
            UserRoleType::HOTEL_CLERK->value,

        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RoomRate $roomRate): bool
    {
        if ($user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value])) {
            return $user->userHotels->pluck('hotel_id')->contains($roomRate->room->hotel_id);
        }

        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RoomRate $roomRate): bool
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RoomRate $roomRate): bool
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RoomRate $roomRate): bool
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RoomRate $roomRate): bool
    {
        return $this->viewAny($user);
    }
}
