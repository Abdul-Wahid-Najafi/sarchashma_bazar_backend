<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(['register', 'resetPassword'])],

            'identifier' => [
                'required',
                'string',
                'email', 
                function ($attribute, $value, $fail) {
                    $type = $this->input('type');

                    if ($type === 'register') {
                        $userExists = \App\Models\User::where('email', $value)->exists();
                        if ($userExists) {
                            $fail('This email is already registered.');
                        }
                    }

                    if ($type === 'resetPassword') {
                        $userExists = \App\Models\User::where('email', $value)->exists();
                        if (!$userExists) {
                            $fail('No account found with this email.');
                        }
                    }
                },
            ],
        ];
    }
}
