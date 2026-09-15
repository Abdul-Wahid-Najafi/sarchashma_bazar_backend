<?php

namespace App\Http\Requests\Api\Chat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['text', 'image', 'file', 'location', 'product'])],

            'product_id' => ['required_if:type,product', 'nullable', 'integer', 'exists:products,id'],

            'body' => ['required_if:type,text', 'nullable', 'string', 'max:5000'],

            'attachment' => [
                'required_if:type,image',
                'required_if:type,file',
                'nullable',
                'file',
                'max:20480', // 20MB
            ],

            'latitude'  => ['required_if:type,location', 'nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['required_if:type,location', 'nullable', 'numeric', 'between:-180,180'],
            'location_label' => ['nullable', 'string', 'max:255'],
        ];
    }
}