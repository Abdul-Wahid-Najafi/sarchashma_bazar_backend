<?php

namespace App\Actions\Profile;

use App\Models\User;

class GetProfileAction
{
    public function execute(User $user): User
    {
        return $user;
    }
}
