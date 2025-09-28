<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getCart($request);
        return response()->json($cart->load('items.product'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getCart($request);

        $item = $cart->items()->updateOrCreate(
            ['product_id' => $request->product_id],
            ['quantity' => $request->quantity]
        );

        return response()->json($item);
    }

    public function updateItem(Request $request, CartItem $item)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $item->update(['quantity' => $request->quantity]);

        return response()->json($item);
    }

    public function removeItem(Request $request, CartItem $item)
    {
        $item->delete();
        return response()->json(['message' => 'Item removed']);
    }

    public function clear(Request $request)
    {
        $cart = $this->getCart($request);
        $cart->items()->delete();

        return response()->json(['message' => 'Cart cleared']);
    }

    private function getCart(Request $request)
    {
        return Cart::firstOrCreate(['user_id' => $request->user()->id ?? null]);
    }
}
