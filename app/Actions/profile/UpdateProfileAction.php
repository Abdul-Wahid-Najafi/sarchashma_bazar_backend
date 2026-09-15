<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UpdateProfileAction
{
    public function execute(User $user, array $data): User
    {
       
         info('My Request Data:', $data);

        if (isset($data['profile_picture'])) {
            // حذف تصویر قبلی
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // ذخیره تصویر جدید
            $data['profile_picture'] = $data['profile_picture']
                ->store('profiles', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        /*
        |--------------------------------------------------------------------------
        | Seller
        |--------------------------------------------------------------------------
        */

        // $data['is_seller'] = (bool) $data['is_seller'];

        // if (!$data['is_seller']) {
        //     $data['shop_name'] = null;
        // }

        /*
        |--------------------------------------------------------------------------
        | Update User
        |--------------------------------------------------------------------------
        */
        $data['is_personal_profile_complete'] = true;

        $user->update($data);

        return $user->refresh();
    }
}

