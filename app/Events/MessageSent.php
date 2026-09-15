<?php

namespace App\Events;

use App\Http\Resources\Api\Chat\MessageResource;
use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
            new PrivateChannel('user.' . $this->recipientId()),
        ];
    }

    protected function recipientId(): int
    {
        $conversation = $this->message->conversation;

        return $conversation->user_one_id === $this->message->sender_id
            ? $conversation->user_two_id
            : $conversation->user_one_id;
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return ['message' => new MessageResource($this->message)];
    }
}