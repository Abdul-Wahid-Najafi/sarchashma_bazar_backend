<?php

namespace App\Actions\Chat;

use App\Models\Conversation;
use App\Models\User;

class StartConversationAction
{
    public function execute(User $currentUser, int $recipientId): Conversation
    {
        [$userOneId, $userTwoId] = $currentUser->id < $recipientId
            ? [$currentUser->id, $recipientId]
            : [$recipientId, $currentUser->id];

        return Conversation::firstOrCreate([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);
    }
}