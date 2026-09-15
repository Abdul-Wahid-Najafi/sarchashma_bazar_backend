<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopSocialAccount extends Model
{
    protected $fillable = ['shop_id', 'social_icon_id', 'link'];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function socialIcon(): BelongsTo
    {
        return $this->belongsTo(SocialIcon::class);
    }
}
