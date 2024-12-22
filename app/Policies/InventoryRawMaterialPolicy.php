<?php

namespace App\Policies;

use App\Models\RawMaterialInventory;
use App\Models\User;

class InventoryRawMaterialPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RawMaterialInventory $rawMaterialInventory): bool
    {
        return $user->id === $rawMaterialInventory->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RawMaterialInventory $rawMaterialInventory): bool
    {
        return $user->id === $rawMaterialInventory->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RawMaterialInventory $rawMaterialInventory): bool
    {
        return $user->id === $rawMaterialInventory->user_id;
    }
}
