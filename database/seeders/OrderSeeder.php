<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $products = Product::take(2)->get();

        if (! $user || $products->isEmpty()) {
            return;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-SEED-001',
            'subtotal' => 0,
            'total' => 0,
            'status' => 'paid',
        ]);

        $total = 0;

        foreach ($products as $product) {
            $qty = rand(1, 2);
            $price = $product->sale_price ?? $product->price;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'price' => $price,
            ]);

            $total += $qty * $price;
        }

        $order->update([
            'subtotal' => $total,
            'total' => $total,
        ]);
    }
}
