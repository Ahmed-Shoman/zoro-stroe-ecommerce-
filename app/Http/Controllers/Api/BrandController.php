<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Resources\BrandResource;

class BrandController extends Controller
{
    public function index()
    {
        return BrandResource::collection(
            Brand::where('is_active', true)->get()
        );
    }

    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create($request->validated());

        return new BrandResource($brand);
    }
}
