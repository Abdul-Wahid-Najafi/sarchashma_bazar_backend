<?php

namespace App\Http\Resources\Api\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'conversation_id' => $this->conversation_id,
            'sender_id'       => $this->sender_id,
            'type'            => $this->type,
            'body'            => $this->body,

            'attachment_url'  => $this->attachment_path
                ? Storage::disk('public')->url($this->attachment_path)
                : null,
            'attachment_name' => $this->attachment_name,
            'attachment_size' => $this->attachment_size,
            'attachment_mime' => $this->attachment_mime,

            'product' => $this->when(
                $this->type === 'product' && $this->relationLoaded('product') && $this->product,
                function () {
                    return [
                        'id'    => $this->product->id,
                        'name'  => $this->product->name,
                        'slug'  => $this->product->slug,
                        'price' => $this->product->price,
                        'image' => $this->product->images->isNotEmpty()
                            ? Storage::disk('public')->url($this->product->images->first()->image)
                            : null,
                    ];
                },
            ),

            'latitude'        => $this->latitude,
            'longitude'       => $this->longitude,
            'location_label'  => $this->location_label,

            'status'          => $this->status,
            'created_at'      => $this->created_at->toIso8601String(),
        ];
    }
}