<?php

namespace App\Actions\Shop;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateShopSocialsAction
{
    public function execute(User $user, array $socialsData): void
    {
        // بارگذاری رابطه شاپ کاربر برای دسترسی به دکان او
        $shop = $user->shop;

        if (!$shop) {
            abort(404, 'Shop not found for this seller.');
        }

        // استفاده از Transaction برای امنیت بالای دیتابیس در زمان حذف و ثبت همزمان
        DB::transaction(function () use ($shop, $socialsData) {
            // ۱. پاک کردن اکانت‌های اجتماعی قدیمی این دکان
            $shop->socialAccounts()->delete();

            // ۲. ثبت دانه‌به‌دانه اکانت‌های جدید دریافت شده از فلاتر
            foreach ($socialsData as $social) {
                $shop->socialAccounts()->create([
                    'name' => $social['name'],
                    'link' => $social['link'],
                    'icon' => $social['icon'] ?? null,
                ]);
            }
        });
    }
}
