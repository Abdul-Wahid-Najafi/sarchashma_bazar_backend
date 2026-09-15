<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

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

                    // ۱. سناریوی ثبت نام (Register)
                    if ($type === 'register') {
                        // چک کردن اینکه آیا کاربر فعال در سیستم وجود دارد؟
                        $activeUserExists = User::where('email', $value)->exists();
                        
                        if ($activeUserExists) {
                            $fail('Email already exists. Please log in or use a different email.');
                        }
                        
                        // نکته طلایی: اگر حساب سافت دلیت شده باشد، اجازه عبور داده می‌شود تا در مرحله بعد بازیابی شود.
                    }

                    // ۲. سناریوی فراموشی رمز عبور (Reset Password)
                    if ($type === 'resetPassword') {
                        // در فراموشی رمز، کاربر حتماً باید وجود داشته باشد (حتی به صورت سافت دلیت شده)
                        $userExists = User::withTrashed()->where('email', $value)->exists();
                        
                        if (!$userExists) {
                            $fail('No user account found with this email.');
                        }
                    }
                },
            ],
        ];
    }
}
