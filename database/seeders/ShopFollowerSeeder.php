<?php

namespace Database\Seeders;

use App\Models\ShopFollower;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShopFollowerSeeder extends Seeder
{
    public function run(): void
    {
        $shop = Shop::first();
        $user = User::first();

        // اگر دکان و کاربر وجود داشته باشند، یک رابطه فالو نمونه ایجاد می‌کند
        if ($shop && $user) {
            ShopFollower::firstOrCreate([
                'shop_id' => $shop->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
