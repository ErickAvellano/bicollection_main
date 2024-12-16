<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $table = 'product_reviews';

    protected $primaryKey = 'product_reviews_id';

    protected $fillable = [
        'product_id',
        'order_id',
        'customer_id',
        'username',
        'rating',
        'merchant_service_rating',
        'platform_rating',
        'review_text',
        'review_date',
        'is_approved',
        'image_1',
        'image_2',
        'image_3',
        'image_4',
        'image_5',
    ];

    protected $casts = [
        'review_date' => 'datetime',
        'is_approved' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function customerImage()
    {
        return $this->hasOne(CustomerImage::class, 'customer_id', 'customer_id');
    }
}
