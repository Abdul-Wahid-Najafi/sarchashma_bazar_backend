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
         Log::info('VerifyOtpAction Started', [
            'identifier' => $identifier,
            'code' => $code
        ]);
       
        $otp = Otp::isValid($identifier, $code)->first();

        if (!$otp) {
            throw ValidationException::withMessages([
                'code' => ['incorrect code or expired'],
            ]);
        }

        $otp->update(['is_used' => true]);

        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        $user = User::firstOrCreate(
            [$field => $identifier],
            ['status' => UserStatus::ACTIVE->value]
        );

        if ($user->status === UserStatus::BANNED->value) {
            throw ValidationException::withMessages([
                'identifier' => ['your account is banned'],
            ]);
        }

        return $user;
    }
}