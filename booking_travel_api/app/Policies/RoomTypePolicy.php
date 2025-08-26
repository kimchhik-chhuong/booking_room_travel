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
        // Allow admin to update any room type
        if ($user->hasRole('admin')) {
            return true;
        }

        // For hotel owners, check if they own the hotel this room type belongs to
        if ($roomType->hotelMetadata && $roomType->hotelMetadata->user_id) {
            return $roomType->hotelMetadata->user_id === $user->id;
        }

        // Deny by default
        return false;
    }

    /**
     * Determine whether the user can delete the room type.
     */
    public function delete(User $user, RoomType $roomType): bool
    {
        return $this->update($user, $roomType);
    }

    /**
     * Determine whether the user can view the room type.
     */
    public function view(User $user, RoomType $roomType): bool
    {
        // Allow viewing if the user can update (same permissions)
        return $this->update($user, $roomType);
    }
}
