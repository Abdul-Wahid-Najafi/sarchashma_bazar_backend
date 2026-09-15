<?php

namespace Database\Seeders;

use App\Models\ShopSocialAccount;
use App\Models\Shop;
use App\Models\SocialIcon;
use Illuminate\Database\Seeder;

class ShopSocialAccountSeeder extends Seeder
{
    public function run(): void
    {
        $shop = Shop::first();

        if ($shop) {
            // پیدا کردن آی‌دی آیکون‌ها از جدول جدید
            $facebookIcon = SocialIcon::where('name', 'Facebook')->first();
            $instagramIcon = SocialIcon::where('name', 'Instagram')->first();

            if ($facebookIcon) {
                ShopSocialAccount::create([
                    'shop_id' => $shop->id,
                    'social_icon_id' => $facebookIcon->id, // استفاده از کلید خارجی جدید
                    'link' => 'https://facebook.com',
                ]);
            }

            if ($instagramIcon) {
                ShopSocialAccount::create([
                    'shop_id' => $shop->id,
                    'social_icon_id' => $instagramIcon->id,
                    'link' => 'https://instagram.com',
                ]);
            }
        }
    }
}
