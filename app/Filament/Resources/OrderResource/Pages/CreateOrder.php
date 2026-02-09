<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\OrderItem;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['order_number'] = 'ORD-' . now()->timestamp;
        $data['status'] = 'pending';
        $data['subtotal'] = 0;
        $data['total'] = 0;

        return $data;
    }

    protected function afterCreate(): void
    {
        DB::transaction(function () {

            $total = 0;

            foreach ($this->data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->track_quantity) {
                    if ($product->quantity < $item['quantity'] && ! $product->allow_backorder) {
                        throw new \Exception("{$product->name} out of stock");
                    }
                    $product->decrement('quantity', $item['quantity']);
                }

                OrderItem::create([
                    'order_id'   => $this->record->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $product->sale_price ?? $product->price,
                ]);

                $total += ($product->sale_price ?? $product->price) * $item['quantity'];
            }

            $this->record->update([
                'subtotal' => $total,
                'total'    => $total,
            ]);
        });
    }
}