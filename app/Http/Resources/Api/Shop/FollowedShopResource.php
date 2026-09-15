<?php

namespace App\Http\Resources\Api\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FollowedShopResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'shop_name'       => $this->shop_name,
            'slug'            => $this->slug,
            'logo'            => $this->logo ? Storage::disk('public')->url($this->logo) : null,
            'is_verified'     => (bool) $this->is_verified,
            'followers_count' => $this->followers_count, 
            'products_count'  => $this->products_count,  
        ];
    }
}