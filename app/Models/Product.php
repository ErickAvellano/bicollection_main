<?php

// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'merchant_id',
        'product_name',
        'quantity_item',
        'price',
        'description',
        'category_id',
        'subcategory_id',
        'product_status',
        'created_at'
    ];

    // Define relationships
    public function images()
    {
        return $this->hasMany(ProductImg::class, 'product_id', 'product_id');
    }
    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id', 'product_id');
    }
    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id', 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id', 'category_id');
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'merchant_id', 'merchant_id');
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }
     public function getAverageRatingAttribute()
     {
         return round($this->reviews->avg('rating'), 1) ?? 0; 
     }
     public function bicollectionSales()
    {
        return $this->hasMany(BicollectionSales::class, 'product_id');
    }

}
