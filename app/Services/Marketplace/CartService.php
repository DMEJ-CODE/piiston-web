<?php

namespace App\Services\Marketplace;

use App\Models\Marketplace\ProductListing;
use App\Models\Marketplace\ShoppingCart;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getCart()
    {
        return ShoppingCart::firstOrCreate(['user_id' => Auth::id()]);
    }

    public function addToCart(ProductListing $listing, int $quantity)
    {
        $cart = $this->getCart();
        $item = $cart->items()->where('listing_id', $listing->id)->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'listing_id' => $listing->id,
                'quantity' => $quantity,
                'unit_price' => $listing->price,
            ]);
        }

        return $cart->load('items.listing.part');
    }

    public function removeFromCart(int $itemId)
    {
        $cart = $this->getCart();

        return $cart->items()->where('id', $itemId)->delete();
    }
}
