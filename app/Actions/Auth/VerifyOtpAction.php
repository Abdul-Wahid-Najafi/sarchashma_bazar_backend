<?php

namespace App\Actions\Auth;

use App\Models\Otp;
use App\Models\User;
use App\Enums\UserStatus;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class VerifyOtpAction
{
    public function execute(string $identifier, string $code): User
    {
       
        $otp = Otp::isValid($identifier, $code)->first();

        if (!$otp) {
            throw ValidationException::withMessages([
                'code' => ['incorrect code or expired'],
            ]);
        }

        $otp->update(['is_used' => true]);

        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
        $field = $isEmail ? 'email' : 'phone_number';

        $user = User::withTrashed()->where($field, $identifier)->first();
         
        if ($user) {
            if ($user->trashed()) {
                $user->restore(); 
                $user->update(['status' => UserStatus::ACTIVE->value]);
            }
        } else {
           
            $user = User::forceCreate([
                'email'        => $isEmail ? $identifier : null,
                'phone_number' => !$isEmail ? $identifier : null,
                'status'       => UserStatus::ACTIVE->value
            ]);

        }

        if ($user->status === UserStatus::BANNED->value) {
            throw ValidationException::withMessages([
                'identifier' => ['your account is banned'],
            ]);
        }
        $user->refresh();
        return $user;
    }
}
