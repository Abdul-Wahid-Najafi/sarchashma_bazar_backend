<?php

namespace App\Http\Requests\Api\Shop;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopSocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->is_seller;
    }

    public function rules(): array
    {
        return [
            // استفاده از sometimes یعنی این فیلد فقط در زمان ساخت اجباری است و در زمان ویرایش می‌تواند فرستاده نشود
            'name' => ['sometimes', 'required', 'string', 'max:50'], 
            'link' => ['required', 'url', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
        ];
    }
}
