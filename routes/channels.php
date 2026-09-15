<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);

    return $conversation && in_array($user->id, [$conversation->user_one_id, $conversation->user_two_id]);
});

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('presence-conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (!$conversation || !in_array($user->id, [$conversation->user_one_id, $conversation->user_two_id])) {
        return null;
    }

    return [
        'id'   => $user->id,
        'name' => trim($user->first_name . ' ' . $user->last_name),
    ];
});