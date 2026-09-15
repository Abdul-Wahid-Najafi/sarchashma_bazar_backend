<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'conversation_id', 'sender_id', 'product_id', 'type', 'body', // 🔧 product_id اضافه شد
    'attachment_path', 'attachment_name', 'attachment_size', 'attachment_mime',
    'latitude', 'longitude', 'location_label',
    'status', 'delivered_at', 'read_at',
];



    protected $casts = [
        'delivered_at' => 'datetime',
        'read_at'      => 'datetime',
        'latitude'     => 'decimal:7',
        'longitude'    => 'decimal:7',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}