<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SocialIcon extends Model
{
    protected $fillable = ['name', 'icon_url'];

    public function getIconUrlAttribute($value): string
    {
        return asset(Storage::url($value));
    }
}
