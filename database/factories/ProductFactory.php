<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'shop_id'     => Shop::inRandomOrder()->value('id'),
            'category_id' => Category::whereNotNull('parent_id')->inRandomOrder()->value('id'),
            'name'        => ucfirst($name),
            'slug'        => Str::slug($name) . '-' . Str::random(5),
            'description' => fake()->paragraph(),
            'price'       => fake()->randomFloat(2, 10, 5000),
            'stock'       => fake()->numberBetween(1, 20),
            'condition'   => fake()->randomElement(['new', 'used', 'refurbished']),
            'status'      => 'published',
        ];
    }
}