<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'first_name'   => ['required', 'string', 'max:50'],
            'last_name'    => ['required', 'string', 'max:50'],
            'password'=>['required','min:8'],
            'phone_number'     => ['required','nullable', 'string', 'regex:/^[0-9]{9,14}$/','unique:users,phone_number,' . $userId],
            'whatsapp'     => ['nullable', 'string', 'regex:/^[0-9]{9,14}$/','unique:users,whatsapp,' . $userId],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            
            'is_seller'    => ['required'],
            'shop_name'    => ['required_if:is_seller,true', 'nullable', 'string', 'max:100', ],
        ];
    }
}
