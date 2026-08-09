<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginWithEmailAction
{
    public function execute(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['incorrect email or password'],
            ]);
        }

        if ($user->status === UserStatus::BANNED->value) {
            throw ValidationException::withMessages([
                'email' => ['your account is banned'],
            ]);
        }

        return $user;
    }
}
