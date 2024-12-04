<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientCollecton extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'clients' => $this->collection->map(function ($client) {
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'address' => $client->address,
                    'phones' => $client->phones ? $client->phones->map(function ($phone) {
                        return [
                            'phone_id' => $phone->id,
                            'phone_number' => $phone->phone_number
                        ];
                    }) : []
                ];
            }),
            'total' => $this->collection->count()
        ];
    }
}
