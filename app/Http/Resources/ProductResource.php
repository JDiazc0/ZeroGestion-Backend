<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'selling_price' => $this->selling_price,
            'inventory' => $this->when($this->relationLoaded('inventory'), function () {
                return [
                    'id' => $this->inventory->id,
                    'quantity' => $this->inventory->quantity,
                ];
            })
        ];
    }
}
