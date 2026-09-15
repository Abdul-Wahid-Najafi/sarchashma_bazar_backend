<?php

namespace App\Actions\Chat;

use App\Events\MessageStatusUpdated;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class MarkMessagesReadAction
{
    public function execute(Conversation $conversation, User $currentUser): array
    {
        $messageIds = $conversation->messages()
            ->where('sender_id', '!=', $currentUser->id)
            ->whereIn('status', ['sent', 'delivered'])
            ->pluck('id');

        if ($messageIds->isEmpty()) {
            return [];
        }

        Message::whereIn('id', $messageIds)->update([
            'status'  => 'read',
            'read_at' => now(),
        ]);

        broadcast(new MessageStatusUpdated($conversation->id, $messageIds->toArray(), 'read'))->toOthers();

        return $messageIds->toArray();
    }
}