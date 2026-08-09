<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateProfileAction
{
    public function execute(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            
            if (isset($data['profile_picture']) && $data['profile_picture']->isValid()) {
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                $path = $data['profile_picture']->store('profiles', 'public');
                $data['profile_picture'] = $path;
            }

            $isSeller = (bool) $data['is_seller'];
            $shopName = $data['shop_name'] ?? null;

        
            unset($data['shop_name']); 

            $user->update($data);

            if ($isSeller && $shopName) {
                Shop::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'shop_name' => $shopName,
                        'slug'      => Str::slug($shopName) ?: Str::random(8), // ساخت اسلاگ خودکار
                        'whatsapp'  => $user->whatsapp,
                    ]
                );
            }

            return $user;
        });
    }
}
