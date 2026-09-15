<?php

namespace App\Http\Resources\Api\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $otherUser = $this->otherUser($request->user()->id);

        return [
            'id' => $this->id,
            'is_blocked_by_me' => $request->user()->hasBlocked($otherUser->id),
            'has_blocked_me'   => $request->user()->isBlockedBy($otherUser->id),
            'other_user' => [
                'id'              => $otherUser->id,
                'name'            => trim($otherUser->first_name . ' ' . $otherUser->last_name),
                'profile_picture' => $otherUser->profile_picture
                    ? Storage::disk('public')->url($otherUser->profile_picture)
                    : null,
                'last_seen_at'    => $otherUser->last_seen_at?->toIso8601String(),
            ],

            'last_message' => $this->whenLoaded(
                'latestMessage',
                fn () => $this->latestMessage ? new MessageResource($this->latestMessage) : null,
            ),

            'unread_count' => (int) ($this->unread_count ?? 0),
            'updated_at'   => $this->updated_at->toIso8601String(),
        ];
    }
}