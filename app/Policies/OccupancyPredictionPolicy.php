<?php

namespace App\Policies;

use App\Enum\UserRoleType;
use App\Models\OccupancyPrediction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OccupancyPredictionPolicy
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
    public function view(User $user, OccupancyPrediction $occupancyPrediction): bool
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
    public function update(User $user, OccupancyPrediction $occupancyPrediction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OccupancyPrediction $occupancyPrediction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OccupancyPrediction $occupancyPrediction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OccupancyPrediction $occupancyPrediction): bool
    {
        return false;
    }
}
