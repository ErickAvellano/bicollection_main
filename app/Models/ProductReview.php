<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    // Specify the table name if it's not the default pluralized form
    protected $table = 'product_reviews';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'product_reviews_id';

    // Define the attributes that are mass assignable
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

    // Define the attributes that should be cast to specific types
    protected $casts = [
        'review_date' => 'datetime',
        'is_approved' => 'boolean',
    ];

    /**
     * Define a relationship with the Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Define a relationship with the User model.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function customerImage()
    {
        return $this->hasOne(CustomerImage::class, 'customer_id', 'customer_id');
    }
}
