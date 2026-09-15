<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Cviebrock\EloquentSluggable\Sluggable;

class Shop extends Model
{
    use HasFactory, SoftDeletes , Sluggable;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function socialAccounts(): HasMany
    {
        return $this->hasMany(ShopSocialAccount::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shop_followers')
                    ->withTimestamps();
    }

    public function services(): HasMany
    {
        return $this->hasMany(ShopService::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'shop_name' // نام فیلدی که اسلاگ از روی آن ساخته می‌شود
            ]
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
