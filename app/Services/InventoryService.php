<?php

namespace App\Services;

use App\Http\Requests\UpdateInventoryProductRequest;
use App\Models\ProductInventory;
use App\Models\RawMaterialInventory;
use Illuminate\Database\Eloquent\Collection;

class InventoryService
{
    public function getAll(): Collection
    {
        $user_id = auth()->id();

        $productInventory = ProductInventory::where('user_id', $user_id)
            ->with('product')
            ->get()
            ->map(function ($item) {
                $item->type = 'product';
                return $item;
            });

        $rawMaterialInventory = RawMaterialInventory::where('user_id', $user_id)
            ->with('rawMaterial')
            ->get()
            ->map(function ($item) {
                $item->type = 'raw_material';
                return $item;
            });

        $inventories = $productInventory->concat($rawMaterialInventory);

        return $inventories;
    }

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
