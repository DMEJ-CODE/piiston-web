<?php

namespace App\Http\Controllers\Api\v1\Marketplace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketplace\ProductResource;
use App\Http\Resources\Marketplace\StoreResource;
use App\Repositories\Marketplace\ProductRepositoryInterface;
use App\Repositories\Marketplace\StoreRepositoryInterface;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    protected $storeRepository;

    protected $productRepository;

    public function __construct(StoreRepositoryInterface $storeRepository, ProductRepositoryInterface $productRepository)
    {
        $this->storeRepository = $storeRepository;
        $this->productRepository = $productRepository;
    }

    public function show(int $id): JsonResponse
    {
        $store = $this->storeRepository->findById($id);
        if (! $store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        return response()->json(new StoreResource($store));
    }

    public function products(int $id): JsonResponse
    {
        $products = $this->productRepository->getStoreProducts($id);

        return response()->json(ProductResource::collection($products)->response()->getData(true));
    }
}
