<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'shop_id', 'category_id', 'name', 'slug', 'description',
        'price', 'stock', 'condition', 'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class)->orderBy('sort_order');
    }
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'product_favorites')->withTimestamps();
    }
}