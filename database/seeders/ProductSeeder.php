<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory(20)->create()->each(function (Product $product) {

            // ۱ تا ۳ عکس نمونه برای هر محصول
            $imageCount = rand(1, 3);
            for ($i = 0; $i < $imageCount; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => 'products/placeholder-' . rand(1, 5) . '.jpg',
                    'sort_order' => $i,
                ]);
            }

            // چند اتریبیوت آزاد نمونه
            $sampleAttributes = [
                ['key' => 'RAM', 'value' => '8GB'],
                ['key' => 'Storage', 'value' => '128GB'],
                ['key' => 'Color', 'value' => 'Black'],
            ];

            foreach ($sampleAttributes as $index => $attr) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'key'        => $attr['key'],
                    'value'      => $attr['value'],
                    'sort_order' => $index,
                ]);
            }
        });
    }
}