<?php

namespace App\Services;

use App\Models\RawMaterial;
use App\Models\RawMaterialInventory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RawMaterialService
{
    /**
     * Get all raw materials
     *
     * @param integer $user_id
     * @return Collection
     */
    public function getAll(int $userId): Collection
    {
        return RawMaterial::with('inventory')
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * Create new raw material
     *
     * @param array $data
     * @param integer $initialStock
     * @return RawMaterial
     */
    public function create(array $data, float $initialStock = 0): RawMaterial
    {
        return DB::transaction(function () use ($data, $initialStock) {
            $rawMaterial = RawMaterial::create($data);

            $rawMaterial->inventory()->create([
                'quantity' => $initialStock,
                'user_id' => $data['user_id']
            ]);

            return $rawMaterial;
        });
    }

    /**
     * Find raw material by id
     *
     * @param integer $rawMaterialId
     * @return RawMaterial
     */
    public function find(int $rawMaterialId): RawMaterial
    {
        return RawMaterial::with('inventory')->findOrFail($rawMaterialId);
    }

    /**
     * Updates existing raw material
     *
     * @param integer $rawMaterialId
     * @param array $data
     * @return RawMaterial
     */
    public function update(int $rawMaterialId, array $data): RawMaterial
    {
        $rawMaterial = $this->find($rawMaterialId);
        $rawMaterial->update($data);
        return $rawMaterial;
    }

    /**
     * Delete existing raw material
     *
     * @param integer $rawMaterialId
     * @return void
     */
    public function delete(int $rawMaterialId): void
    {
        $rawMaterial = $this->find($rawMaterialId);
        $rawMaterial->delete();
    }
}
