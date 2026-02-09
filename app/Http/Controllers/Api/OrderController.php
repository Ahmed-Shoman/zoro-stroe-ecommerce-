<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();

        $cart = Cart::with('items.product')
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty',
            ], 422);
        }

        try {
            DB::transaction(function () use ($cart, $user) {

                $subtotal = $cart->items->sum(
                    fn ($item) => $item->price * $item->quantity
                );

                // create order
                $order = Order::create([
                    'user_id'      => $user->id,
                    'order_number' => 'ORD-' . now()->timestamp,
                    'subtotal'     => $subtotal,
                    'total'        => $subtotal,
                    'status'       => 'pending',
                ]);

                foreach ($cart->items as $item) {
                    $product = $item->product;

                    if ($product->track_quantity) {
                        if (
                            $product->quantity < $item->quantity &&
                            ! $product->allow_backorder
                        ) {
                            throw new \Exception(
                                "Product {$product->name} is out of stock"
                            );
                        }

                        $product->decrement('quantity', $item->quantity);
                    }

                    // create order item (snapshot)
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'quantity'   => $item->quantity,
                        'price'      => $item->price,
                    ]);
                }

                $cart->items()->delete();
            });

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Order created successfully',
        ]);
    }
}
