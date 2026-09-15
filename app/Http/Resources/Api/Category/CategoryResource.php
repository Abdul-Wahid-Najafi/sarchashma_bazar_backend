<?php

namespace App\Http\Resources\Api\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'image'     => $this->image ? Storage::disk('public')->url($this->image) : null,
            'parent_id' => $this->parent_id,
            'children'  => CategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}