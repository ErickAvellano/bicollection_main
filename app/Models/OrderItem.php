<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Specify the table name (in case Laravel doesn't auto-detect it)
    protected $table = 'order_item';

    // Define the primary key column
    protected $primaryKey = 'order_item_id';

    // Indicate if the IDs are auto-incrementing
    public $incrementing = true;

    // Specify the primary key type

    // Define the fillable attributes
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

    // Define the relationships
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
    // In OrderItem.php
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}
