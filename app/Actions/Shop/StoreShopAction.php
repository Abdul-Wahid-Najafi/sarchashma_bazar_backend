<?php

namespace App\Actions\Shop;

use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class StoreShopAction
{
    public function execute(User $user, array $data): Shop
    {
        if ($user->shop()->exists()) {
            abort(422, 'You already have a registered shop.');
        }

        return DB::transaction(function () use ($user, $data) {
            
            $logoPath = null;
            $coverPath = null;

            // ۱. بررسی و ذخیره‌سازی لوگو در فولدر public/shop
            if (isset($data['logo']) && $data['logo']->isValid()) {
                $logoPath = $data['logo']->store('shop', 'public');
            }

            // ۲. بررسی و ذخیره‌سازی کاور در فولدر public/shop
            if (isset($data['cover_image']) && $data['cover_image']->isValid()) {
                $coverPath = $data['cover_image']->store('shop', 'public');
            }

            // ۳. ثبت اطلاعات نهایی در دیتابیس
            $shop = Shop::create([
                'user_id'        => $user->id,
                'province_id'    => $data['province_id'],
                'shop_name'      => $data['shop_name'],
                'description'    => $data['description'] ?? null,
                'contact_number' => $data['contact_number'],
                'whatsapp'       => $data['whatsapp'] ?? null,
                'website'        => $data['website'] ?? null,
                'logo'           => $logoPath, // ذخیره آدرس فایل (مثال: shop/xyz.jpg)
                'cover_image'    => $coverPath,
                'is_verified'    => false,
                'status'         => 'active',
            ]);

            $shop->services()->createMany(
                collect($data['services'] ?? [])
                    ->map(fn ($service) => [
                        'service' => $service,
                    ])
                    ->toArray()
            );
            $shop->socialAccounts()->createMany(
                collect($data['socials'] ?? [])
                    ->map(fn ($social) => [
                        'social_icon_id' => $social['social_icon_id'],
                        'link' => $social['link'],
                    ])
                    ->toArray()
            );

            $user->update(['is_seller' => true,'is_shop_profile_complete' => true]);
            

            return $shop;
        });
    }
}
