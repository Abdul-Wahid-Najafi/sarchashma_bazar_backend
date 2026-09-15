<?php

namespace App\Actions\Shop;

use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateShopAction
{
    public function execute(Shop $shop, array $data): Shop
    {
        return DB::transaction(function () use ($shop, $data) {

            // ==========================================
            // 1. Logo
            // ==========================================
            if (isset($data['logo']) && $data['logo']->isValid()) {

                if ($shop->logo) {
                    Storage::disk('public')->delete($shop->logo);
                }

                $data['logo'] = $data['logo']->store('shop', 'public');

            } else {
                unset($data['logo']);
            }


            // ==========================================
            // 2. Cover Image
            // ==========================================
            if (isset($data['cover_image']) && $data['cover_image']->isValid()) {

                if ($shop->cover_image) {
                    Storage::disk('public')->delete($shop->cover_image);
                }

                $data['cover_image'] = $data['cover_image']->store(
                    'shop',
                    'public'
                );

            } else {
                unset($data['cover_image']);
            }


            // ==========================================
            // 3. جدا کردن Services و Socials
            // ==========================================

            $services = $data['services'] ?? null;
            $socials = $data['socials'] ?? null;

            unset($data['services']);
            unset($data['socials']);


            // ==========================================
            // 4. Update Shop
            // ==========================================

            $shop->update($data);


            // ==========================================
            // 5. Update Services
            // ==========================================

            if ($services !== null) {

                // حذف سرویس‌های قبلی
                $shop->services()->delete();

                // ایجاد سرویس‌های جدید
                $shop->services()->createMany(
                    collect($services)
                        ->map(fn ($service) => [
                            'service' => $service,
                        ])
                        ->toArray()
                );
            }


            // ==========================================
            // 6. Update Social Accounts
            // ==========================================

            if ($socials !== null) {

                // حذف Social Account های قبلی
                $shop->socialAccounts()->delete();

                // ایجاد Social Account های جدید
                $shop->socialAccounts()->createMany(
                    collect($socials)
                        ->map(fn ($social) => [
                            'social_icon_id' => $social['social_icon_id'],
                            'link' => $social['link'],
                        ])
                        ->toArray()
                );
            }


            // ==========================================
            // 7. Profile Complete
            // ==========================================

            $shop->user->update([
                'is_shop_profile_complete' => true,
            ]);


            // ==========================================
            // 8. Reload relationships
            // ==========================================

            $shop->load([
                'user',
                'province.country',
                'services',
                'socialAccounts',
            ]);

            return $shop;
        });
    }
}