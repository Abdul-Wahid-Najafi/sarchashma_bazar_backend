<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateProductAction
{
    public function execute(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update([
                'category_id' => $data['category_id'],
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'price'       => $data['price'],
                'stock'       => $data['stock'],
                'condition'   => $data['condition'],
                'status'      => 'pending', 
            ]);

            if (!empty($data['removed_image_ids'])) {
                $imagesToRemove = $product->images()
                    ->whereIn('id', $data['removed_image_ids'])
                    ->get();

                foreach ($imagesToRemove as $image) {
                    Storage::disk('public')->delete($image->image);
                    $image->delete();
                }
            }

            if (!empty($data['new_images'])) {
                $currentMaxOrder = $product->images()->max('sort_order') ?? -1;

                foreach ($data['new_images'] as $index => $file) {
                    $path = $file->store('products', 'public');

                    $product->images()->create([
                        'image'      => $path,
                        'sort_order' => $currentMaxOrder + $index + 1,
                    ]);
                }
            }

            if (array_key_exists('attributes', $data)) {
                $product->attributes()->delete();

                foreach ($data['attributes'] ?? [] as $index => $attribute) {
                    $product->attributes()->create([
                        'key'        => $attribute['key'],
                        'value'      => $attribute['value'],
                        'sort_order' => $index,
                    ]);
                }
            }

            return $product->load('images', 'attributes', 'category', 'shop');
        });
    }
}