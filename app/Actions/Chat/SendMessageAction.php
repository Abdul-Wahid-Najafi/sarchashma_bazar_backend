<?php

namespace App\Actions\Chat;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class SendMessageAction
{
    public function execute(Conversation $conversation, User $sender, array $data): Message
    {
        $payload = [
            'conversation_id' => $conversation->id,
            'sender_id'       => $sender->id,
            'type'            => $data['type'],
            'status'          => 'sent',
        ];

        if ($data['type'] === 'text') {
            $payload['body'] = $data['body'];
        }

        if (in_array($data['type'], ['image', 'file']) && isset($data['attachment'])) {
            $file = $data['attachment'];
            $folder = $data['type'] === 'image' ? 'chat/images' : 'chat/files';
            $path = $file->store($folder, 'public');

            $payload['attachment_path'] = $path;
            $payload['attachment_name'] = $file->getClientOriginalName();
            $payload['attachment_size'] = $file->getSize();
            $payload['attachment_mime'] = $file->getMimeType();
        }

        if ($data['type'] === 'location') {
            $payload['latitude']       = $data['latitude'];
            $payload['longitude']      = $data['longitude'];
            $payload['location_label'] = $data['location_label'] ?? null;
        }

        if ($data['type'] === 'product') {
            $payload['product_id'] = $data['product_id'];
            $payload['body'] = $data['body'] ?? null;
        }

        $message = Message::create($payload);

        $conversation->touch();

        broadcast(new MessageSent($message->load(['sender', 'product.images'])))->toOthers();

        return $message;
    }
}