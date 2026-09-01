<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->cart()->with('items.listing.part.brand')->firstOrCreate(['user_id' => Auth::id()]);

        return response()->json($cart);
    }

    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'listing_id' => 'required|exists:product_listings,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Auth::user()->cart()->firstOrCreate(['user_id' => Auth::id()]);

        $item = $cart->items()->updateOrCreate(
            ['listing_id' => $validated['listing_id']],
            ['quantity' => \DB::raw('quantity + '.$validated['quantity'])]
        );

        return response()->json($item, 201);
    }

    public function removeItem($itemId)
    {
        $cart = Auth::user()->cart;
        if (! $cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cart->items()->where('id', $itemId)->delete();

        return response()->json(['message' => 'Item removed']);
    }
}
