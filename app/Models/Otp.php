<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_used' => 'boolean',
        ];
    }

    public function scopeIsValid($query, $identifier, $code)
    {
        return $query->where('identifier', $identifier)
            ->where('code', $code)
            ->where('is_used', false)
            ->where('expires_at', '>', now());
    }
}