<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InventoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'inventories' => $this->collection->map(function ($inventory) {
                return [
                    'id' => $inventory->id,
                    'type' => $inventory->type,
                    'name' => $inventory->type === 'product' ? $inventory->product->name : $inventory->rawMaterial->name,
                    'quantity' => $inventory->quantity,
                ];
            }),
            'total' => $this->collection->count()
        ];
    }
}
