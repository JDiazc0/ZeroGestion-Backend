<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * Get all products
     *
     * @param integer $userId
     * @return Collection
     */
    public function getAll(int $userId): Collection
    {
        return Product::with(['inventory', 'rawMaterials'])
            ->where('user_id', $userId)
            ->get();
    }

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
