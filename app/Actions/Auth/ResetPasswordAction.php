<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordAction
{

    public function execute(User $user, string $newPassword): User
    {
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        $user->tokens()->delete();

        return $user;
    }
}
