<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(
            Product::where('is_active', true)
                ->with(['category', 'brand'])
                ->get()
        );
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return new ProductResource($product->load(['category', 'brand']));
    }
}
