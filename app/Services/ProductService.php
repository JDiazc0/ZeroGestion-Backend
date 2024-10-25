<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * Create new product
     *
     * @param array $data
     * @param integer $initalStock
     * @return Product
     */
    public function create(array $data, array $rawMaterials, float $initialStock = 0): Product
    {
        return DB::transaction(function () use ($data, $rawMaterials, $initialStock) {
            $product = Product::create($data);

            $rawMaterialsData = collect($rawMaterials)
                ->mapWithKeys(fn($material) => [
                    $material['raw_material_id'] => ['quantity' => $material['quantity']]
                ])
                ->all();

            $product->rawMaterials()->attach($rawMaterialsData);

            $product->inventory()->create([
                'quantity' => $initialStock,
                'user_id' => $data['user_id']
            ]);

            return $product;
        });
    }
}
