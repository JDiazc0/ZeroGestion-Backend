<?php

namespace App\Services;

use App\Http\Requests\UpdateInventoryProductRequest;
use App\Models\ProductInventory;
use App\Models\RawMaterialInventory;

class InventoryService
{
    /**
     *  Update the specified product in storage
     *  @param array $data
     *  @return ProductInventory
     */
    public function updateProductInventory(array $data): ProductInventory
    {
        $productInventory = ProductInventory::findOrFail($data['id']);
        $productInventory->update(['quantity' => $data['quantity']]);

        return $productInventory;
    }

    /**
     *  Update the specified raw material in storage
     *  @param array $data
     *  @return RawMaterialInventory
     */
    public function updateRawMaterialInventory(array $data): RawMaterialInventory
    {
        $rawMaterialInventory = RawMaterialInventory::findOrFail($data['id']);
        $rawMaterialInventory->update(['quantity' => $data['quantity']]);

        return $rawMaterialInventory;
    }
}
