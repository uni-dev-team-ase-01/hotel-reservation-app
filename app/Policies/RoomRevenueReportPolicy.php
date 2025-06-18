<?php

namespace App\Policies;

use App\Enum\UserRoleType;
use App\Models\RoomRevenueReport;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoomRevenueReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            UserRoleType::HOTEL_MANAGER->value,
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RoomRevenueReport $roomRevenueReport): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RoomRevenueReport $roomRevenueReport): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RoomRevenueReport $roomRevenueReport): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RoomRevenueReport $roomRevenueReport): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RoomRevenueReport $roomRevenueReport): bool
    {
        return false;
    }
}
