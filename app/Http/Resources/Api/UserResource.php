<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'firstName'      => $this->first_name,
            'lastName'       => $this->last_name,
            'email'          => $this->email,
            'password'          => $this->password,
            'phoneNumber'    => $this->phone_number,
            'whatsapp'       => $this->whatsapp,
            'isSeller'       => $this->is_seller,
            'profilePicture' => $this->profile_picture,
            
            'shop'           => $this->whenLoaded('shop', function() {
                return [
                    'id'       => $this->shop->id,
                    'shopName' => $this->shop->shop_name,
                    'slug'     => $this->shop->slug,
                ];
            }) ?? ($this->shop ? [
                'id'       => $this->shop->id,
                'shopName' => $this->shop->shop_name,
                'slug'     => $this->shop->slug,
            ] : null),
        ];
    }
}
