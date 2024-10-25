<?php

namespace App\Policies;

use App\Models\RawMaterial;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RawMaterialPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RawMaterial $rawMaterial): bool
    {
        return $user->id === $rawMaterial->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RawMaterial $rawMaterial): bool
    {
        return $user->id === $rawMaterial->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RawMaterial $rawMaterial): bool
    {
        return $user->id === $rawMaterial->user_id;
    }
}
