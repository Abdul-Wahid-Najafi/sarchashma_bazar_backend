<?php

namespace App\Http\Requests\Api\Shop;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShopSocialsRequest extends FormRequest
{
    public function authorize(): bool
    {
        // مطمئن می‌شویم کاربر لاگین است و دسترسی فروشنده دارد
        return $this->user() && $this->user()->is_seller;
    }

    public function rules(): array
    {
        return [
            'socials' => ['required', 'array', 'min:1'],
            'socials.*.name' => ['required', 'string', 'max:50'], 
            'socials.*.link' => ['required', 'url', 'max:255'],  
            'socials.*.icon' => ['nullable', 'string', 'max:100'],  
        ];
    }
}
