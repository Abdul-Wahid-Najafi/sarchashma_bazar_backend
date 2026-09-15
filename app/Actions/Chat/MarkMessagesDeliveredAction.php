<?php

namespace App\Actions\Chat;

use App\Events\MessageStatusUpdated;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class MarkMessagesDeliveredAction
{
    public function execute(Conversation $conversation, User $currentUser): array
    {
        $messageIds = $conversation->messages()
            ->where('sender_id', '!=', $currentUser->id)
            ->where('status', 'sent')
            ->pluck('id');

        if ($messageIds->isEmpty()) {
            return [];
        }

        Message::whereIn('id', $messageIds)->update([
            'status'       => 'delivered',
            'delivered_at' => now(),
        ]);

        broadcast(new MessageStatusUpdated($conversation->id, $messageIds->toArray(), 'delivered'))->toOthers();

        return $messageIds->toArray();
    }
}