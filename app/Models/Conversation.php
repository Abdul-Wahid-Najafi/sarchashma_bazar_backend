<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Conversation extends Model
{
    protected $fillable = ['user_one_id', 'user_two_id'];

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function otherUser(int $currentUserId): User
    {
        return $this->user_one_id === $currentUserId ? $this->userTwo : $this->userOne;
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_one_id', $userId)->orWhere('user_two_id', $userId);
    }

    public function deletedAtFor(int $userId): ?\Carbon\Carbon
    {
        if($this->user_one_id === $userId) return $this->user_one_deleted_at;
        if($this->user_two_id === $userId) return $this->user_two_deleted_at;
        return null;
    }

    public function markDeletedFor(int $userId): void
    {
        if($this->user_one_id ===$userId){
            $this->update(['user_one_deleted_at'=>now()]);
        }
        elseif($this->user_two_id ===$userId){
            $this->update(['user_two_deleted_at'=>now()]);
        }
    }
}