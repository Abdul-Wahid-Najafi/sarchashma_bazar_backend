<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Api\Shop\ShopResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'firstName'      => $this->first_name,
            'lastName'       => $this->last_name,
            'email'          => $this->email,
            'phoneNumber'    => $this->phone_number,
            'whatsapp'       => $this->whatsapp,
            'isSeller'       => $this->is_seller,
            'is_shop_profile_complete' => $this->is_shop_profile_complete,
            'is_personal_profile_complete' => $this->is_personal_profile_complete,
            'profilePicture' => $this->profile_picture
                ? Storage::disk('public')->url($this->profile_picture)
                : null,

            'shop' => new ShopResource($this->whenLoaded('shop')),
        ];
    }
}