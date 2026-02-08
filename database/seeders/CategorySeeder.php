<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Main Categories (Parents)
        |--------------------------------------------------------------------------
        */
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => Str::slug('Electronics'),
            'icon' => 'categories/icons/electronics.svg',
            'parent_id' => null,
            'is_active' => true,
        ]);

        $gaming = Category::create([
            'name' => 'Gaming',
            'slug' => Str::slug('Gaming'),
            'icon' => 'categories/icons/gaming.svg',
            'parent_id' => null,
            'is_active' => true,
        ]);

        $accessories = Category::create([
            'name' => 'Accessories',
            'slug' => Str::slug('Accessories'),
            'icon' => 'categories/icons/accessories.svg',
            'parent_id' => null,
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Electronics Sub Categories
        |--------------------------------------------------------------------------
        */
        Category::insert([
            [
                'name' => 'Laptops',
                'slug' => Str::slug('Laptops'),
                'parent_id' => $electronics->id,
                'is_active' => true,
            ],
            [
                'name' => 'Monitors',
                'slug' => Str::slug('Monitors'),
                'parent_id' => $electronics->id,
                'is_active' => true,
            ],
            [
                'name' => 'Storage & RAM',
                'slug' => Str::slug('Storage & RAM'),
                'parent_id' => $electronics->id,
                'is_active' => true,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Gaming Sub Categories
        |--------------------------------------------------------------------------
        */
        Category::insert([
            [
                'name' => 'Consoles',
                'slug' => Str::slug('Consoles'),
                'parent_id' => $gaming->id,
                'is_active' => true,
            ],
            [
                'name' => 'Controllers',
                'slug' => Str::slug('Controllers'),
                'parent_id' => $gaming->id,
                'is_active' => true,
            ],
            [
                'name' => 'Gaming Accessories',
                'slug' => Str::slug('Gaming Accessories'),
                'parent_id' => $gaming->id,
                'is_active' => true,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Accessories Sub Categories
        |--------------------------------------------------------------------------
        */
        Category::insert([
            [
                'name' => 'Keyboards',
                'slug' => Str::slug('Keyboards'),
                'parent_id' => $accessories->id,
                'is_active' => true,
            ],
            [
                'name' => 'Mouse',
                'slug' => Str::slug('Mouse'),
                'parent_id' => $accessories->id,
                'is_active' => true,
            ],
            [
                'name' => 'Headsets',
                'slug' => Str::slug('Headsets'),
                'parent_id' => $accessories->id,
                'is_active' => true,
            ],
        ]);
    }
}
