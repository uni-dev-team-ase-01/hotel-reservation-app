<?php

namespace App\Policies;

use App\Enum\UserRoleType;
use App\Models\Bill;
use App\Models\User;

class BillPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            UserRoleType::HOTEL_CLERK->value,
            UserRoleType::HOTEL_MANAGER->value,
            UserRoleType::TRAVEL_COMPANY->value,
            UserRoleType::CUSTOMER->value,
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Bill $bill): bool
    {
        if ($user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value])) {
            $reservation = $bill->reservation;

            return $reservation ? $user->userHotels()->where('hotel_id', $reservation->hotel_id)->exists() : false;
        }

        if ($user->hasAnyRole([UserRoleType::TRAVEL_COMPANY->value, UserRoleType::CUSTOMER->value])) {
            return $bill->reservation && $bill->reservation->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasAnyRole([UserRoleType::TRAVEL_COMPANY->value, UserRoleType::CUSTOMER->value])) {
            return false;
        }

        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Bill $bill): bool
    {
        if ($user->hasAnyRole([UserRoleType::TRAVEL_COMPANY->value, UserRoleType::CUSTOMER->value])) {
            return false;
        }

        return $this->view($user, $bill);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Bill $bill): bool
    {
        return $this->update($user, $bill);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Bill $bill): bool
    {
        return $this->update($user, $bill);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Bill $bill): bool
    {
        return $this->update($user, $bill);
    }
}
