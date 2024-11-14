<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth()->id();
        $products = $this->productService->getAll($user_id);

        return response()->json([
            'message' => 'Product retrieved successfully',
            'data' => new ProductCollection($products)
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $initalStock = $request->input('initial_stock', 0);

        $productData = [
            'name' => $data['name'],
            'selling_price' => $data['selling_price'],
            'user_id' => auth()->id(),
        ];

        $rawMaterials = $data['raw_materials'];

        $product = $this->productService->create($productData, $rawMaterials, $initalStock);

        return response()->json([
            'message' => 'Product created succesfully',
            'data' => new ProductResource($product)
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $productId)
    {
        $product = $this->productService->find($productId);

        $this->authorize('view', $product);

        return response()->json([
            'message' => 'Product retrieved successfully',
            'data' => new ProductResource($product)
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
