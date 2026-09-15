<?php

namespace App\Http\Resources\Api\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Api\UserResource;

class ShopResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shop_name' => $this->shop_name,
            'slug' => $this->slug,
            'description' => $this->description,
            'contact_number' => $this->contact_number,
            'whatsapp' => $this->whatsapp,
            'website' => $this->website,

            'logo' => $this->logo ? Storage::disk('public')->url($this->logo) : null,
            'cover_image' => $this->cover_image ? Storage::disk('public')->url($this->cover_image) : null,

            'is_verified' => (bool) $this->is_verified,
            'status' => $this->status,

            // آمار عددی فالوورها
            'followers_count' => $this->followers()->count(),
            'products_count' => $this->products()->count(),
            // بررسی اینکه آیا کاربر فعلی این دکان را فالو کرده است یا خیر (برای دکمه فالو در فلاتر)
             'is_followed_by_me' => $request->user('sanctum')
            ? $this->followers()->where('user_id', $request->user('sanctum')->id)->exists()
            : false,

            // اطلاعات جدول مالک دکان (User)
            'user' => new UserResource($this->user),

            // اطلاعات جدول ولایت (Province)
            'address' => [
                'country' => [
                    'id' => $this->province->country->id,
                    'name' => $this->province->country->name,
                ],
                'province' => [
                    'id' => $this->province->id,
                    'name' => $this->province->name,
                ],
            ],

            'services' => $this->services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'service' => $service->service,
                ];
            }),

            // لیست کامل شبکه‌های اجتماعی دکان (ShopSocialAccounts)
            'social_accounts' => $this->socialAccounts->map(function ($social) {
                return [
                    'id' => $social->id,
                    'social_icon_id' => $social->social_icon_id,
                    'name' => $social->socialIcon?->name,
                    'link' => $social->link,
                    'icon_url' => $social->socialIcon?->icon_url,
                ];

                
            }),

            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}