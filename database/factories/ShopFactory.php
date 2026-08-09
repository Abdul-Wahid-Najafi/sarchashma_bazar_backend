<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShopFactory extends Factory
{
    public function definition(): array
    {
        $shopName = fake()->company();
        return [
            // ایجاد یک کاربر با وضعیت فروشنده و متصل کردن آن
            'user_id' => User::factory()->seller(), 
            'shop_name' => $shopName,
            'slug' => Str::slug($shopName),
            'description' => fake()->paragraph(),
            'contact_number' => fake()->phoneNumber(),
            'whatsapp' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'logo' => fake()->imageUrl(200, 200, 'business'),
            'cover_image' => fake()->imageUrl(800, 400, 'business'),
            'is_verified' => fake()->boolean(30), // ۳۰ درصد احتمال دارد تایید شده باشد
        ];
    }
}