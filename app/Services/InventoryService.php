<?php

namespace App\Services;

use App\Http\Requests\UpdateInventoryProductRequest;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\ProductRawMaterial;
use App\Models\RawMaterial;
use App\Models\RawMaterialInventory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

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

    public function checkInventory(array $products)
    {
        $result = [
            'products_info' => [],
            'material_info' => [],
            'required_purchase' => [],
            'total_purchase_cost' => 0,
            'total_profit' => 0,
        ];

        $totalRequiredMaterials = [];

        foreach ($products as $product) {
            [$productInfo, $requiredMaterials] = $this->checkProductInventory($product['product_id'], $product['quantity']);
            $result['products_info'][] = $productInfo;

            if ($requiredMaterials !== null) {
                foreach ($requiredMaterials as $material) {
                    $materialId = $material['raw_material_id'];
                    if (!isset($totalRequiredMaterials[$materialId])) {
                        $totalRequiredMaterials[$materialId] = $material;
                    } else {
                        $totalRequiredMaterials[$materialId]['needed_quantity'] += $material['needed_quantity'];
                    }
                }
            }

            $result['total_profit'] += $productInfo['sell_price'];
        }

        foreach ($totalRequiredMaterials as $material) {
            $materialInventoryStatus = $this->checkMaterialInventory(
                $material['raw_material_id'],
                $material['needed_quantity']
            );

            $result['material_info'][] = $materialInventoryStatus;

            if ($materialInventoryStatus['status'] === 'buy_required') {
                $costUnit = $material['cost'];
                $unitsToBuy = ceil($materialInventoryStatus['quantity_to_buy'] / $materialInventoryStatus['min_quantity_sell']);
                $totalCost = $unitsToBuy * $costUnit;

                $result['required_purchase'][] = [
                    'material_id' => $materialInventoryStatus['material_id'],
                    'material_name' => $materialInventoryStatus['material_name'],
                    'quantity_to_buy' => $materialInventoryStatus['quantity_to_buy'],
                    'cost_per_unit' => $costUnit,
                    'units_to_buy' => $unitsToBuy,
                    'total_cost' => $totalCost,
                ];

                $result['total_purchase_cost'] += $totalCost;
                $result['total_profit'] -= $totalCost;
            }
        }


        return $result;
    }

    public function checkProductInventory(int $productId, int $quantity)
    {
        $productInventory = ProductInventory::where('product_id', $productId)
            ->with('product')
            ->first();

        $productsInfo = [
            'product_id' => $productId,
            'product_name' => $productInventory->product->name,
            'quantity_required' => $quantity,
            'available' => $productInventory->quantity,
            'sell_price' => $productInventory->product->selling_price * $quantity,
        ];

        if ($productInventory->quantity >= $quantity) {
            return [
                array_merge($productsInfo, [
                    'status' => 'available',
                    'source' => 'stock',
                    'quantity_to_produce' => 0
                ]),
                null
            ];
        }

        $toProduce = $quantity - $productInventory->quantity;
        $requiredMaterials = $this->calculateRequiredMaterials($productId, $toProduce);

        return [
            array_merge($productsInfo, [
                'status' => 'production_required',
                'quantity_to_produce' => $toProduce,
            ]),
            $requiredMaterials
        ];
    }

    public function calculateRequiredMaterials(int $productId, int $toProduce)
    {
        $productMaterials = ProductRawMaterial::with('rawMaterial')
            ->where('product_id', $productId)
            ->get();

        return $productMaterials->map(function ($material) use ($toProduce) {
            return [
                'raw_material_id' => $material->raw_material_id,
                'raw_material_name' => $material->rawMaterial->name,
                'needed_quantity' => $material->quantity * $toProduce,
                'cost' => $material->rawMaterial->cost
            ];
        })->all();
    }

    public function checkMaterialInventory(int $materialId, int $quantity)
    {
        $materialInventory = RawMaterialInventory::where('raw_material_id', $materialId)
            ->with('rawMaterial')
            ->first();

        $materialInfo = [
            'material_id' => $materialId,
            'material_name' => $materialInventory->rawMaterial->name,
            'quantity_required' => $quantity,
            'available' => $materialInventory->quantity,
        ];

        if ($materialInventory->quantity >= $quantity) {
            return array_merge($materialInfo, [
                'status' => 'available',
                'source' => 'stock',
                'quantity_to_buy' => 0
            ]);
        }

        $toBuy = $quantity - $materialInventory->quantity;

        return array_merge($materialInfo, [
            'status' => 'buy_required',
            'source' => 'purchase',
            'quantity_to_buy' => $toBuy,
            'min_quantity_sell' => $materialInventory->rawMaterial->min_quantity,
        ]);
    }
}
