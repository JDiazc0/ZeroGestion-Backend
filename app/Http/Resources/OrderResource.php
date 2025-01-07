<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'client_id' => $this->client_id,
            'delivery_date' => $this->delivery_date,
            'status' => $this->status,
            'order_products' => $this->when($this->relationLoaded('orderProducts'), function () {
                return $this->orderProducts->map(function ($orderedProduct) {
                    return [
                        'product_id' => $orderedProduct->id,
                        'quantity' => $orderedProduct->quantity
                    ];
                });
            })
        ];
    }
}
