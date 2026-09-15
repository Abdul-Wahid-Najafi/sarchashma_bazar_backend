<?php

namespace App\Http\Resources\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Api\Category\CategoryResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->shop->user_id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'price'       => $this->price,
            'stock'       => $this->stock,
            'condition'   => $this->condition,
            'status'      => $this->status,
            'shop_id'     => $this->shop_id,

            // 🆕 نسخه سبک شاپ — بدون N+1 (بدون followers_count و is_followed_by_me)
            'shop' => $this->whenLoaded('shop', function () {
                return [
                    'id'          => $this->shop->id,
                    'shop_name'   => $this->shop->shop_name,
                    'slug'        => $this->shop->slug,
                    'logo'        => $this->shop->logo
                        ? Storage::disk('public')->url($this->shop->logo)
                        : null,
                    'is_verified' => (bool) $this->shop->is_verified,
                ];
            }),

            'is_favorited_by_me' => (bool) ($this->is_favorited_by_me ?? false),

            'category'    => new CategoryResource($this->whenLoaded('category')),
            'images'      => ProductImageResource::collection($this->whenLoaded('images')),
            'attributes'  => ProductAttributeResource::collection($this->whenLoaded('attributes')),
            'created_at'  => $this->created_at->toIso8601String(),
        ];
    }
}