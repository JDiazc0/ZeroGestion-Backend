<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientPhone;
use Illuminate\Database\Eloquent\Collection;

class ClientService
{
    /**
     * Get all clients
     *
     * @param integer $userId
     * @return Collection
     */
    public function getAll(int $userId): Collection
    {
        return Client::with('phones')
            ->where('user_id', $userId)
            ->get();
    }

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

    /**
     * Find client by Id
     *
     * @param integer $clientId
     * @return Client
     */
    public function find(int $clientId): Client
    {
        return Client::with('phones')->findOrFail($clientId);
    }

    /**
     * Updates existing client
     *
     * @param integer $clientId
     * @param array $data
     * @return Client
     */
    public function update(int $clientId, array $data): Client
    {
        $client = $this->find($clientId);
        $client->update($data);

        if (isset($data['phones']) && is_array($data['phones'])) {
            $phonesToKeep = [];

            foreach ($data['phones'] as $phone) {
                if (isset($phone['phone_id'])) {
                    $existingPhone = $client->phones()->find($phone['phone_id']);

                    if ($existingPhone) {
                        $existingPhone->update(['phone_number' => $phone['phone_number']]);
                        $phonesToKeep[] = $phone['phone_id'];
                    }
                } else {
                    $newPhone = $client->phones()->create(['phone_number' => $phone['phone_number']]);
                    $phonesToKeep[] = $newPhone->id;
                }
            }

            $client->phones()->whereNotIn('id', $phonesToKeep)->delete();
        }

        $client->load('phones');

        return $client;
    }

    /**
     *  Delete existing client
     * 
     *  @param integer $clientId
     *  @return void
     */
    public function delete(int $clientId): void
    {
        $client = $this->find($clientId);
        $client->delete();
    }
}
