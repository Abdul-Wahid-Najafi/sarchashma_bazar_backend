<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [];


    // protected $fillable = [
    //     'is_personal_profile_complete',
    // ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_seller' => 'boolean',
            'is_shop_profile_complete' => 'boolean',
            'is_personal_profile_complete' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            if ($user->shop) {
                $user->shop()->delete(); 
            }
            if ($user->socialAccounts()->exists()) {
                $user->socialAccounts()->delete();
            }
        });

        static::restored(function ($user) {
            $trashedShop = $user->shop()->onlyTrashed()->first();
            if ($trashedShop) {
                $trashedShop->restore(); 
            }
            if ($user->socialAccounts()->onlyTrashed()->exists()) {
                $user->socialAccounts()->onlyTrashed()->restore();
            }
        });
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }
    public function followedShops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'shop_followers')->withTimestamps();
    }
    public function favoriteProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_favorites')->withTimestamps();
    }


    public function blockedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'blocker_id', 'blocked_id')->withTimestamps();
    }

    public function blockedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'blocked_id', 'blocker_id')->withTimestamps();
    }

    public function hasBlocked(int $userId): bool
    {
        return $this->blockedUsers()->where('blocked_id', $userId)->exists();
    }

    public function isBlockedBy(int $userId): bool
    {
        return $this->blockedByUsers()->where('blocker_id', $userId)->exists();
    }
}