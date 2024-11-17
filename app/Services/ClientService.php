<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientPhone;

class ClientService
{
    /**
     * Create new client and add phone numbers
     *
     * @param array $data
     * @return Client
     */
    public function create(array $data): Client
    {
        $client = Client::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'user_id' => auth()->id(),
        ]);

        if (isset($data['phones']) && is_array($data['phones'])) {
            $phones = collect($data['phones'])->map(function ($phoneData) {
                return new ClientPhone(['phone_number' => $phoneData['phone_number']]);
            });

            $client->phones()->saveMany($phones);
        }

        return $client->load('phones');
    }
}
