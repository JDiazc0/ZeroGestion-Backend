<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'phones' => $this->when($this->relationLoaded('phones'), function () {
                return $this->phones->map(function ($phones) {
                    return [
                        'id' => $phones->id,
                        'phone_number' => $phones->phone_number,
                    ];
                });
            })
        ];
    }
}
