<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BicollectionSales extends Model
{
    use HasFactory;

    protected $table = 'bicollection_sales';

    // Specify the primary key
    protected $primaryKey = 'sales_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'product_id',
        'customer_id',
        'merchant_id',
        'quantity',
        'total_price',
        'sale_date',
    ];

    public $timestamps = true;

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'merchant_id', 'merchant_id');
    }
}
