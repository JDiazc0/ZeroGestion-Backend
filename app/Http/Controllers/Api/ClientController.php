<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientCollecton;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Services\ClientService;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth()->id();
        $clients = $this->clientService->getAll($user_id);

        return response()->json([
            'message' => 'Clients retrieved successfully',
            'data' => new ClientCollecton($clients)
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $data = $request->validated();

        $client = $this->clientService->create($data);

        return response()->json([
            'message' => 'Client created succesfully',
            'data' => new ClientResource($client)
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $clientId)
    {
        $client = $this->clientService->find($clientId);

        $this->authorize('view', $client);

        return response()->json([
            'message' => 'Client retrieved successfully',
            'data' => new ClientResource($client)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $this->authorize('update', $client);

        $data = $request->validated();
        $clientUpdated = $this->clientService->update($client->id, $data);

        return response()->json([
            'message' => 'Client updated succesfully',
            'data' => new ClientResource($clientUpdated)
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);

        $this->clientService->delete($client->id);
    }
}
