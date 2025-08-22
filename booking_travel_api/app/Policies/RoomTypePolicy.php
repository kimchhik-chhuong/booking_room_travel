<?php

namespace App\Policies;

use App\Models\RoomType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoomTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the room type.
     */
    public function update(User $user, RoomType $roomType): bool
    {
        // Always allow admin
        if ($user->hasRole('admin')) {
            return true;
        }

        // For non-admin users, check ownership
        return $roomType->hotelMetadata && 
               $roomType->hotelMetadata->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the room type.
     */
    public function delete(User $user, RoomType $roomType): bool
    {
        return $this->update($user, $roomType);
    }
}
