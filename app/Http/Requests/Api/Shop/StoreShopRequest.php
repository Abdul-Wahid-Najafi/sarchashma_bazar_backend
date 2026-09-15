<?php

namespace App\Http\Requests\Api\Shop;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
   public function rules(): array
    {
        return [
            'shop_name' => ['required', 'string', 'min:3', 'max:100'],

            'province_id' => [
                'required',
                'integer',
                'exists:provinces,id'
            ],

            'description' => [
                'nullable',
                'string',
                'max:300'
            ],

            'contact_number' => [
                'required',
                'string',
                'max:20'
            ],

            'whatsapp' => [
                'nullable',
                'string',
                'max:20'
            ],

            'website' => [
                'nullable',
                'url',
                'max:255'
            ],

            // Services
            'services' => [
                'nullable',
                'array',
                'max:20'
            ],

            'services.*' => [
                'required',
                'string',
                'max:255'
            ],

            // Social accounts
            'socials' => [
                'nullable',
                'array',
                'max:7'
            ],

            'socials.*.social_icon_id' => [
                'required',
                'integer',
                'exists:social_icons,id'
            ],

            'socials.*.link' => [
                'required',
                'string',
                'url',
                'max:500'
            ],

            // Images
            'logo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
'cover_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
