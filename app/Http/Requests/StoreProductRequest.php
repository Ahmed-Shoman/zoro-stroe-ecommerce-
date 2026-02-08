<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id'   => 'required|exists:categories,id',
            'brand_id'      => 'required|exists:brands,id',
            'name'          => 'required|string|max:255',
            'slug'          => 'required|string|unique:products,slug',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'sale_price'    => 'nullable|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'is_new'        => 'boolean',
            'is_bestseller' => 'boolean',
            'is_active'     => 'boolean',
        ];
    }
}
