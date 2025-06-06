<?php

namespace App\Policies;

use App\Enum\UserRoleType;
use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
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
            UserRoleType::CUSTOMER->value,
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        if ($user->hasRole(UserRoleType::SUPER_ADMIN->value)) {
            return true;
        }

        if ($user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value])) {
            return $user->userHotels->pluck('hotel_id')->contains($reservation->hotel_id);
        }

        if ($user->hasRole(UserRoleType::CUSTOMER->value)) {
            return $reservation->user_id === $user->id;
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
    public function update(User $user, Reservation $reservation): bool
    {
        return $user->hasAnyRole([
            UserRoleType::HOTEL_CLERK->value,
            UserRoleType::HOTEL_MANAGER->value,
            UserRoleType::SUPER_ADMIN->value,
        ]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        return $user->hasAnyRole([
            UserRoleType::HOTEL_CLERK->value,
            UserRoleType::HOTEL_MANAGER->value,
            UserRoleType::SUPER_ADMIN->value,
        ]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reservation $reservation): bool
    {
        return $user->hasAnyRole([
            UserRoleType::HOTEL_CLERK->value,
            UserRoleType::HOTEL_MANAGER->value,
            UserRoleType::SUPER_ADMIN->value,
        ]);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reservation $reservation): bool
    {
        return $user->hasAnyRole([
            UserRoleType::HOTEL_CLERK->value,
            UserRoleType::HOTEL_MANAGER->value,
            UserRoleType::SUPER_ADMIN->value,
        ]);
    }
}
