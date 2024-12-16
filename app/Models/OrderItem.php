<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_item';

    protected $primaryKey = 'order_item_id';

    public $incrementing = true;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_price',
        'variation_id',
        'variation_name',
        'quantity',
        'subtotal',
        'created_at',
        'updated_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id', 'product_variation_id');
    }
    public function productImg()
    {
        return $this->hasOne(ProductImg::class, 'product_id', 'product_id');
    }
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}
