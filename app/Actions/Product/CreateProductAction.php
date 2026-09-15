<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateProductAction
{
    public function execute(Shop $shop, array $data): Product
    {
        return DB::transaction(function () use ($shop, $data) {
            $product = Product::create([
                'shop_id'     => $shop->id,
                'category_id' => $data['category_id'],
                'name'        => $data['name'],
                'slug'        => Str::slug($data['name']) . '-' . Str::random(6),
                'description' => $data['description'] ?? null,
                'price'       => $data['price'],
                'stock'       => $data['stock'],
                'condition'   => $data['condition'],
                'status'      => 'pending',
            ]);

            foreach ($data['images'] as $index => $imageFile) {
                $path = $imageFile->store('products', 'public');

                $product->images()->create([
                    'image'      => $path,
                    'sort_order' => $index,
                ]);
            }

            if (!empty($data['attributes'])) {
                foreach ($data['attributes'] as $index => $attribute) {
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