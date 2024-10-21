<?php

namespace App\Services;

use App\Models\RawMaterial;
use App\Models\RawMaterialInventory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RawMaterialService
{


    /**
     * Create new raw material
     *
     * @param array $data
     * @param integer $initalStock
     * @return RawMaterial
     */
    public function create(array $data, float $initalStock = 0): RawMaterial
    {
        return DB::transaction(function () use ($data, $initalStock) {
            $rawMaterial = RawMaterial::create($data);

            $rawMaterial->inventory()->create([
                'quantity' => $initalStock,
                'user_id' => $data['user_id']
            ]);

            return $rawMaterial;
        });
    }
}