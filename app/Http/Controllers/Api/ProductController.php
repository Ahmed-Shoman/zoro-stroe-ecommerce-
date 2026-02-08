<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $query = Product::query()
        ->where('is_active', true)
        ->with('brand');

    // filter by single category
    if ($request->filled('category')) {
        $query->whereHas('categories', function ($q) use ($request) {
            $q->where('categories.id', $request->category);
        });
    }

    // filter by multiple categories
    if ($request->filled('categories')) {
        $categoryIds = $request->categories;
        $query->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        });
    }

    // brand filter
    if ($request->filled('brand')) {
        $query->where('brand_id', $request->brand);
    }

    return ProductResource::collection(
        $query->paginate(12)
    );
}

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return new ProductResource($product->load(['category', 'brand']));
    }
}
