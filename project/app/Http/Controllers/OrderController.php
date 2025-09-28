<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $cart = Cart::with('items.product')->where('user_id', $request->user()->id ?? null)->firstOrFail();
        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $order = Order::create([
            'user_id' => $request->user()->id ?? null,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        $cart->items()->delete();

        return response()->json($order->load('items.product'));
    }

    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)->with('items.product')->get();
        return response()->json($orders);
    }
}
