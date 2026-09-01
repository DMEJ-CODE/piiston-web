<?php

namespace App\Http\Controllers\Api\v1\Marketplace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketplace\ProductResource;
use App\Repositories\Marketplace\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $products = $this->productRepository->search($request->all());

        return response()->json(ProductResource::collection($products)->response()->getData(true));
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->findById($id);
        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(new ProductResource($product));
    }

    public function search(Request $request): JsonResponse
    {
        return $this->index($request);
    }
}
