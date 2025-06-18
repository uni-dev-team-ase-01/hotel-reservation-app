<?php

namespace App\Policies;

use App\Enum\UserRoleType;
use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            UserRoleType::HOTEL_CLERK->value,
            UserRoleType::HOTEL_MANAGER->value,
            // UserRoleType::SUPER_ADMIN->value,
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Room $room): bool
    {
        if ($user->hasRole(UserRoleType::SUPER_ADMIN->value)) {
            return true;
        }

        if ($user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value])) {
            return $user->userHotels->pluck('hotel_id')->contains($room->hotel_id);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            UserRoleType::HOTEL_CLERK->value,
            UserRoleType::HOTEL_MANAGER->value,
            UserRoleType::SUPER_ADMIN->value,
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Room $room): bool
    {
        return $this->view($user, $room);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Room $room): bool
    {
        return $this->view($user, $room);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Room $room): bool
    {
        return $this->view($user, $room);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Room $room): bool
    {
        return $this->view($user, $room);
    }
}
