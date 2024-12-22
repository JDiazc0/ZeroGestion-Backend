<?php

namespace App\Policies;

use App\Models\ProductInventory;
use App\Models\RawMaterialInventory;
use App\Models\User;

class InventoryProductPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductInventory $productInventory): bool
    {
        return $user->id === $productInventory->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductInventory $productInventory): bool
    {
        return $user->id === $productInventory->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductInventory $productInventory): bool
    {
        return $user->id === $productInventory->user_id;
    }
}
