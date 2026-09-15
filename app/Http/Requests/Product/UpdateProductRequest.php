<?php

namespace App\Http\Requests\Product;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product');

        return $product && $product->shop_id === $this->user()->shop?->id;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    $hasChildren = Category::where('parent_id', $value)->exists();
                    if ($hasChildren) {
                        $fail('باید یک زیرکتگوری نهایی انتخاب شود.');
                    }
                },
            ],

            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'condition'   => ['required', Rule::in(['new', 'used', 'refurbished'])],

            'new_images'      => ['nullable', 'array'],
            'new_images.*'    => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'removed_image_ids'   => ['nullable', 'array'],
            'removed_image_ids.*' => [
                'integer',
                Rule::exists('product_images', 'id')->where('product_id', $product->id),
            ],

            'attributes'         => ['nullable', 'array'],
            'attributes.*.key'   => ['required_with:attributes', 'string', 'max:100'],
            'attributes.*.value' => ['required_with:attributes', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $product = $this->route('product');

            $existingCount = $product->images()->count();
            $removedCount  = count($this->input('removed_image_ids', []));
            $newCount      = count($this->file('new_images', []));

            $finalCount = $existingCount - $removedCount + $newCount;

            if ($finalCount < 1) {
                $validator->errors()->add('images', 'image is required.');
            }

            if ($finalCount > 5) {
                $validator->errors()->add('images', 'accepted maximum of 5 images.');
            }
        });
    }
}