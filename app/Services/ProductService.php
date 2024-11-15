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

    /**
     * Find product by Id
     *
     * @param integer $productId
     * @return Product
     */
    public function find(int $productId): Product
    {
        return Product::with(['inventory', 'rawMaterials'])->findOrFail($productId);
    }

    /**
     * Updates existing product
     *
     * @param integer $productId
     * @param array $data
     * @return Product
     */
    public function update(int $productId, array $data): Product
    {
        $product = $this->find($productId);
        $product->update($data);

        if (isset($data['raw_materials'])) {
            $rawMaterialsData = collect($data['raw_materials'])->mapWithKeys(function ($item) {
                return [
                    $item['raw_material_id'] => [
                        'quantity' => $item['quantity'],
                    ],
                ];
            })->toArray();

            $product->rawMaterials()->sync($rawMaterialsData);
        }

        $product->load('rawMaterials');

        return $product;
    }


    /**
     * Delete existing product
     *
     * @param integer $productId
     * @return void
     */
    public function delete(int $productId): void
    {
        $product = $this->find($productId);
        $product->delete();
    }
}
