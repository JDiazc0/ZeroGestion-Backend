<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRawMaterialRequest;
use App\Http\Resources\RawMaterialColletion;
use App\Http\Resources\RawMaterialResource;
use App\Services\RawMaterialService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RawMaterialController extends Controller
{

    protected $rawMaterialService;

    /**
     * Constructor of the controller that injects the RawMaterialService.
     *
     * @param RawMaterialService $rawMaterialService
     * @return void
     */
    public function __construct(RawMaterialService $rawMaterialService)
    {
        $this->rawMaterialService = $rawMaterialService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth()->id();
        $rawMaterials = $this->rawMaterialService->getAll($user_id);

        return response()->json([
            'message' => 'Raw materials retrieved successfully',
            'data' => new RawMaterialColletion($rawMaterials)
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRawMaterialRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $initalStock = $request->input('initial_stock', 0);

        $rawMaterial = $this->rawMaterialService->create($data, $initalStock);

        return response()->json([
            'message' => 'Raw material created successfully',
            'data' => new RawMaterialResource($rawMaterial)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $rawMaterialId)
    {
        $rawMaterial = $this->rawMaterialService->find($rawMaterialId);

        return response()->json([
            'message' => 'Raw material retrieved successfully',
            'data' => new RawMaterialResource($rawMaterial)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
