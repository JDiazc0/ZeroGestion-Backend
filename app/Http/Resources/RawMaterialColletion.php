<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RawMaterialColletion extends ResourceCollection
{
    /**
     * Transform the collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'raw_materials' => $this->collection->map(function ($rawMaterial) {
                return [
                    'id' => $rawMaterial->id,
                    'name' => $rawMaterial->name,
                    'cost' => $rawMaterial->cost,
                    'min_quantity' => $rawMaterial->min_quantity,
                    'measure' => $rawMaterial->measure,
                    'inventory' => $rawMaterial->inventory ? [
                        'id' => $rawMaterial->inventory->id,
                        'quantity' => $rawMaterial->inventory->quantity,
                    ] : null,
                ];
            }),
            'total' => $this->collection->count()
        ];
    }
}
