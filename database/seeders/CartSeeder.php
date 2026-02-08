<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $products = Product::take(3)->get();

        if (! $user || $products->isEmpty()) {
            return;
        }

        $cart = Cart::firstOrCreate([
            'user_id' => $user->id,
        ]);

        foreach ($products as $product) {
            CartItem::updateOrCreate(
                [
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                ],
                [
                    'quantity' => rand(1, 3),
                    'price' => $product->sale_price ?? $product->price,
                ]
            );
        }
    }
}