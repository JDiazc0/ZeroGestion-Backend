<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RawMaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'cost' => $this->cost,
            'min_quantity' => $this->min_quantity,
            'measure' => $this->measure,
            'inventory' => $this->when($this->relationLoaded('inventory'), function () {
                return [
                    'id' => $this->inventory->id,
                    'quantity' => $this->inventory->quantity,
                ];
            })
        ];
    }
}
