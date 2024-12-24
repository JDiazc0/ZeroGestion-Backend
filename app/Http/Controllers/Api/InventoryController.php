<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInventoryProductRequest;
use App\Http\Requests\UpdateInventoryRawMaterialRequest;
use App\Http\Resources\InventoryCollection;
use App\Http\Resources\InventoryProductResource;
use App\Http\Resources\InventoryRawMaterialResource;
use App\Models\ProductInventory;
use App\Models\RawMaterialInventory;
use App\Services\InventoryService;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->inventoryService->getAll();

        return response()->json([
            'message' => 'Inventory retrieved successfully',
            'data' => new InventoryCollection($data)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified product in storage.
     */
    public function updateProductInventory(UpdateInventoryProductRequest $request, ProductInventory $productInventory)
    {
        $this->authorize('update', $productInventory);

        $data = $request->validated();
        $data['id'] = $productInventory->id;

        $data = $this->inventoryService->updateProductInventory($data);

        return response()->json([
            'message' => 'Product inventory created successfully',
            'data' => new InventoryProductResource($data)
        ], Response::HTTP_CREATED);
    }

    /**
     * Update the specified product in storage.
     */
    public function updateRawMaterialInventory(UpdateInventoryRawMaterialRequest $request, RawMaterialInventory $rawMaterialInventory)
    {
        $this->authorize('update', $rawMaterialInventory);

        $data = $request->validated();
        $data['id'] = $rawMaterialInventory->id;

        $data = $this->inventoryService->updateRawMaterialInventory($data);

        return response()->json([
            'message' => 'Product inventory created successfully',
            'data' => new InventoryRawMaterialResource($data)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
