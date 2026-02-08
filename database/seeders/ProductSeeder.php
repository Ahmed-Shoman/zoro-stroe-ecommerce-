<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::first();
        $brand    = Brand::first();

        if (! $category || ! $brand) {
            return;
        }

        Product::create([
            'category_id' => $category->id,
            'brand_id'    => $brand->id,
            'name'        => 'Gaming Laptop',
            'slug'        => Str::slug('Gaming Laptop'),
            'price'       => 25000,
            'sale_price'  => 23000,
            'stock'       => 10,
            'is_new'      => true,
            'is_active'   => true,
        ]);
    }
}