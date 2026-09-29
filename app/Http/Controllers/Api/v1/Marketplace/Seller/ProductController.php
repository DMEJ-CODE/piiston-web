<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketplace\ProductResource;
use App\Models\Documents\DocumentCategory;
use App\Models\Documents\DocumentType;
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

        if (! $seller) {
            return response()->json(['message' => 'Seller profile not found'], 404);
        }

        // Handle JSON strings for array fields when sent via multipart
        foreach (['new_part', 'detailed_delivery_options'] as $field) {
            if ($request->has($field) && is_string($request->$field)) {
                $decoded = json_decode($request->$field, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $request->merge([$field => $decoded]);
                }
            }
        }

        $data = $request->validate([
            'part_id' => 'required_without:new_part|exists:spare_parts,id',
            'new_part' => 'required_without:part_id|array',
            'new_part.name' => 'required_with:new_part|string',
            'new_part.category_id' => 'required_with:new_part|exists:part_categories,id',
            'new_part.brand_id' => 'required_with:new_part|exists:part_brands,id',
            'new_part.part_number' => 'required_with:new_part|string',
            'new_part.quality_grade' => 'nullable|string',
            'new_part.condition' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'professional_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string',
            'currency_id' => 'required|exists:currencies,id',
            'quantity' => 'required|integer|min:0',
            'condition' => 'required|string',
            'delivery_option' => 'nullable|string',
            'detailed_delivery_options' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($seller, $data, $request) {
            if (isset($data['new_part'])) {
                $partData = $data['new_part'];
                $partData['condition'] = $partData['condition'] ?? $data['condition'];
                $partData['quality_grade'] = $partData['quality_grade'] ?? 'AFTERMARKET';
                $partData['description'] = $data['description'] ?? null;

                $part = SparePart::create($partData);
                $data['part_id'] = $part->id;
            }

            $data['seller_id'] = $seller->id;
            $data['status'] = 'active';

            $product = $this->productRepository->create($data);

            // Initialize inventory
            $product->inventory()->create([
                'quantity' => $data['quantity'],
                'minimum_stock' => 5,
            ]);

            // Handle Media Files
            $this->handleMedia($product, $request);

            return response()->json(new ProductResource($product->load(['part', 'documents.mediaFile'])), 201);
        });
    }

    protected function handleMedia($product, Request $request)
    {
        $files = ['image1' => 'IMAGE', 'image2' => 'IMAGE', 'video' => 'VIDEO'];

        $category = DocumentCategory::firstOrCreate(['name' => 'Marketplace']);
        $type = DocumentType::firstOrCreate([
            'category_id' => $category->id,
            'name' => 'Product Media',
        ]);

        foreach ($files as $key => $mediaType) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $path = $file->store('marketplace/products', 'public');

                $doc = $product->documents()->create([
                    'category_id' => $category->id,
                    'type_id' => $type->id,
                    'title' => $key,
                    'file_path' => $path,
                    'file_name' => basename($path),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'visibility' => 'PUBLIC',
                    'status' => 'ACTIVE',
                    'created_by' => Auth::id(),
                ]);

                $doc->mediaFile()->create([
                    'media_type' => $mediaType,
                ]);
            }
        }
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

        // Handle Media Files during update if any are uploaded
        $this->handleMedia($product, $request);

        return response()->json(new ProductResource($product->fresh(['part', 'documents.mediaFile'])));
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
