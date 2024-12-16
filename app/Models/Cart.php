<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart'; 
    protected $primaryKey = 'cart_id'; 

    protected $fillable = [
        'customer_id',
        'merchant_id',
        'quantity',
        'status',
        'product_id',
        'product_variation_id',
        'product_customization_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function shop()
{
    return $this->belongsTo(Shop::class, 'merchant_id', 'merchant_id');
}
}
