<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $cart = Cart::firstOrCreate(
            ['user_id' => $request->user()->id]
        );

        return response()->json([
            'items' => $cart->items()->with('product')->get(),
            'total' => $cart->total,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(
            ['user_id' => $request->user()->id]
        );

        $product = Product::findOrFail($request->product_id);

        $item = $cart->items()->updateOrCreate(
            ['product_id' => $product->id],
            [
                'quantity' => $request->quantity,
                'price' => $product->sale_price ?? $product->price,
            ]
        );

        return response()->json([
            'message' => 'Added to cart',
            'item' => $item,
            'total' => $cart->total,
        ]);
    }

    public function remove(Request $request, $productId)
    {
        $cart = Cart::where('user_id', $request->user()->id)->firstOrFail();
        $cart->items()->where('product_id', $productId)->delete();

        return response()->json([
            'message' => 'Removed',
            'total' => $cart->total,
        ]);
    }
}