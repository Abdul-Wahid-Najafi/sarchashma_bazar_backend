<?php

namespace App\Actions\Auth;

use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class SendOtpAction
{
    public function execute(string $identifier): int
    {
        $lastOtp = Otp::where('identifier', $identifier)
            ->where('is_used', false)
            ->where('created_at', '>', Carbon::now()->subMinute())
            ->first();

        if ($lastOtp) {
            throw ValidationException::withMessages([
                'identifier' => ['please with 1 minute'],
            ]);
        }
        Otp::where('identifier', $identifier)->forceDelete();

        $code = 123456; // mt_rand(100000, 999999);

        Otp::create([
            'identifier' => $identifier,
            'code'       => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // SMS::send($identifier, $code);

        return $code;
    }
}
