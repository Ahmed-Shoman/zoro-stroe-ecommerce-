<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'price'       => $this->price,
            'sale_price'  => $this->sale_price,
            'final_price' => $this->final_price,
            'stock'       => $this->stock,
            'category'    => $this->category->name,
            'brand'       => $this->brand->name,
        ];
    }
}
