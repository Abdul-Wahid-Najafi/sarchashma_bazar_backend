<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identifier' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                    $isPhone = preg_match('/^[0-9]{9,14}$/', $value);

                    if (!$isEmail && !$isPhone) {
                        $fail('not email');
                    }
                }
            ],
            'code'       => ['required', 'string', 'digits:6'], 
        ];
    }
}