<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DeleteProfileAction
{
    public function execute(User $user): void
    {
        /*
        |--------------------------------------------------------------------------
        | Delete Profile Picture
        |--------------------------------------------------------------------------
        */

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Sanctum Tokens
        |--------------------------------------------------------------------------
        */

        $user->tokens()->delete();

        /*
        |--------------------------------------------------------------------------
        | Delete User
        |--------------------------------------------------------------------------
        */

        $user->delete();
    }
}