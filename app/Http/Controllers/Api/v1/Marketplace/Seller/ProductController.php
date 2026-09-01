<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketplace\ProductResource;
use App\Models\Marketplace\SparePart;
use App\Repositories\Marketplace\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        if (! $seller) {
            return response()->json(['message' => 'Seller profile not found'], 404);
        }

        $products = $this->productRepository->getStoreProducts($seller->id);

        return response()->json(ProductResource::collection($products)->response()->getData(true));
    }

    public function store(Request $request): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;

        $data = $request->validate([
            'part_id' => 'required_without:new_part|exists:spare_parts,id',
            'new_part' => 'required_without:part_id|array',
            'new_part.name' => 'required_with:new_part|string',
            'new_part.category_id' => 'required_with:new_part|exists:part_categories,id',
            'new_part.brand_id' => 'required_with:new_part|exists:part_brands,id',
            'new_part.part_number' => 'required_with:new_part|string|unique:spare_parts,part_number',
            'price' => 'required|numeric|min:0',
            'professional_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:product_listings,sku',
            'currency_id' => 'required|exists:currencies,id',
            'quantity' => 'required|integer|min:0',
            'condition' => 'required|string',
            'delivery_option' => 'nullable|string',
            'detailed_delivery_options' => 'nullable|array',
        ]);

        return DB::transaction(function () use ($seller, $data) {
            if (isset($data['new_part'])) {
                $part = SparePart::create($data['new_part']);
                $data['part_id'] = $part->id;
            }

            $data['seller_id'] = $seller->id;
            $data['status'] = 'active';

            $product = $this->productRepository->create($data);

            // Initialize inventory with detailed fields
            $product->inventory()->create([
                'quantity' => $data['quantity'],
                'minimum_stock' => 5,
            ]);

            return response()->json(new ProductResource($product), 201);
        });
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $product = $this->productRepository->findById($id);

        if (! $product || $product->seller_id !== $seller->id) {
            return response()->json(['message' => 'Product not found or access denied'], 403);
        }

        $data = $request->validate([
            'price' => 'sometimes|numeric|min:0',
            'professional_price' => 'sometimes|numeric|min:0',
            'sku' => 'sometimes|string',
            'quantity' => 'sometimes|integer|min:0',
            'status' => 'sometimes|string',
            'delivery_option' => 'nullable|string',
            'detailed_delivery_options' => 'nullable|array',
        ]);

        $this->productRepository->update($id, $data);

        return response()->json(new ProductResource($product->fresh()));
    }

    public function destroy(int $id): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $product = $this->productRepository->findById($id);

        if (! $product || $product->seller_id !== $seller->id) {
            return response()->json(['message' => 'Product not found or access denied'], 403);
        }

        $this->productRepository->delete($id);

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
