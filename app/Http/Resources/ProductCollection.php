<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'products' => $this->collection->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'selling_price' => $product->selling_price,
                    'inventory' => [
                        'id' => $product->inventory->id,
                        'quantity' => $product->inventory->quantity,
                    ],
                    'raw_materials' => $product->rawMaterials ? $product->rawMaterials->map(function ($raw_material) {
                        return [
                            'id' => $raw_material->id,
                            'name' => $raw_material->name,
                            'quantity' => $raw_material->pivot->quantity,
                        ];
                    }) : [],
                ];
            }),
            'total' => $this->collection->count()
        ];
    }
}
