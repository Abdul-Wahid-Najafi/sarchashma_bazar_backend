<?php

namespace App\Http\Requests\Product;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    $hasChildren = Category::where('parent_id', $value)->exists();
                    if ($hasChildren) {
                        $fail('select a child category');
                    }
                },
            ],

            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'condition'   => ['required', Rule::in(['new', 'used', 'refurbished'])],

            'images'      => ['required', 'array', 'min:1', 'max:5'],
            'images.*'    => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'attributes'         => ['nullable', 'array'],
            'attributes.*.key'   => ['required_with:attributes', 'string', 'max:100'],
            'attributes.*.value' => ['required_with:attributes', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'Images are required.',
            'images.max'      => 'Maximum 5 images are allowed.',
        ];
    }
}