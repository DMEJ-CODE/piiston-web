<?php

namespace App\Http\Controllers\Api\v1\Marketplace;

use App\Http\Controllers\Controller;
use App\Repositories\Marketplace\ProductRepositoryInterface;
use App\Services\Marketplace\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    protected $productRepository;

    public function __construct(CartService $cartService, ProductRepositoryInterface $productRepository)
    {
        $this->cartService = $cartService;
        $this->productRepository = $productRepository;
    }

    public function index(): JsonResponse
    {
        $cart = $this->cartService->getCart()->load('items.listing.part');

        return response()->json($cart);
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'listing_id' => 'required|exists:product_listings,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $listing = $this->productRepository->findById($request->listing_id);
        $cart = $this->cartService->addToCart($listing, $request->quantity);

        return response()->json([
            'message' => 'Product added to cart',
            'cart' => $cart,
        ]);
    }

    public function remove(int $itemId): JsonResponse
    {
        $this->cartService->removeFromCart($itemId);

        return response()->json(['message' => 'Item removed from cart']);
    }
}
