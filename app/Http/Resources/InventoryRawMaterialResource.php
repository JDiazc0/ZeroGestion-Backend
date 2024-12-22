<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryRawMaterialResource extends JsonResource
{
    /**
    * Transform the resource into an array.
    *
    * @return array<string, mixed>
    */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'raw_material_id' => $this->raw_material_id,
            'quantity' => $this->quantity,
        ];
    }
}
