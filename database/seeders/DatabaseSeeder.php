<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;
use App\Models\ShopFollower;
use App\Models\ShopSocialAccount;
use App\Models\SocialIcon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ۱. اجرای سیدرهای پایه (بدون ProductSeeder)
        $this->call([
            CountrySeeder::class,
            ProvinceSeeder::class,
            SocialIconSeeder::class,
            CategorySeeder::class,
        ]);

        // ۲. ساخت یک کاربر ادمین ثابت جهت تست‌های لوکال شما
        $admin = User::create([
            'first_name'      => 'Wahid',
            'last_name'       => 'Admin',
            'email'           => 'admin@example.com',
            'phone_number'    => '+93700000000',
            'whatsapp'        => '+93700000000',
            'password'        => bcrypt('password'),
            'is_seller'       => true,
            'status'          => 'active',
        ]);

        $adminShop = Shop::factory()->create([
            'user_id'     => $admin->id,
            'shop_name'   => 'Sarchashma Main Store',
            'province_id' => 1,
        ]);

        // ۳. ساخت فروشنده‌ها و خریدارها
        $sellers = User::factory()->count(5)->create(['is_seller' => true]);

        $shops = collect();
        $shops->push($adminShop);

        foreach ($sellers as $seller) {
            $shop = Shop::factory()->create(['user_id' => $seller->id]);
            $shops->push($shop);
        }

        $buyers = User::factory()->count(15)->create(['is_seller' => false]);

        $allUsers = collect([$admin])->merge($sellers)->merge($buyers);

        // ۴. 🆕 حالا که شاپ‌ها ساخته شدن، محصولات رو seed کن
        $this->call([
            ProductSeeder::class,
        ]);

        // ۵. social accounts + followers (بدون تغییر)
        $allIcons = SocialIcon::all();

        foreach ($shops as $shop) {
            $randomIcons = $allIcons->random(rand(2, min(4, $allIcons->count())));

            foreach ($randomIcons as $icon) {
                ShopSocialAccount::create([
                    'shop_id'        => $shop->id,
                    'social_icon_id' => $icon->id,
                    'link'           => 'https://' . strtolower($icon->name) . '.com/' . str_replace(' ', '', strtolower($shop->shop_name)),
                ]);
            }

            $randomFollowers = $allUsers->random(rand(3, 10));

            foreach ($randomFollowers as $follower) {
                if ($follower->id !== $shop->user_id) {
                    ShopFollower::firstOrCreate([
                        'shop_id' => $shop->id,
                        'user_id' => $follower->id,
                    ]);
                }
            }
        }
    }
}