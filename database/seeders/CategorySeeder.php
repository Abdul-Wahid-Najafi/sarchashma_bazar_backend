<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics'      => ['Mobile Phones', 'Laptops', 'Cameras', 'Tablets'],
            'Vehicles'         => ['Cars', 'Motorcycles', 'Trucks'],
            'Real Estate'      => ['Apartments', 'Houses', 'Land'],
            'Home & Furniture' => ['Sofas', 'Beds', 'Kitchen Appliances'],
            'Fashion'          => ['Men Clothing', 'Women Clothing', 'Shoes'],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::firstOrCreate(
                ['slug' => Str::slug($parentName)],
                ['name' => $parentName]
            );

            foreach ($children as $childName) {
                Category::firstOrCreate(
                    ['slug' => Str::slug($childName)],
                    [
                        'name'      => $childName,
                        'parent_id' => $parent->id,
                    ]
                );
            }
        }
    }
}