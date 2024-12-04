<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BicollectionSales extends Model
{
    use HasFactory;

    // Specify the table name (if it's not the plural form of the model name)
    protected $table = 'bicollection_sales';

    // Specify the primary key
    protected $primaryKey = 'sales_id';

    // Disable auto-increment as we are generating custom sales_id
    public $incrementing = false;

    // Specify the type of the primary key (since it's a string, not an integer)
    protected $keyType = 'string';

    // Mass-assignable attributes
    protected $fillable = [
        'order_id',
        'product_id',
        'customer_id',
        'merchant_id',
        'quantity',
        'total_price',
        'sale_date',
    ];

    // Timestamps for created_at and updated_at
    public $timestamps = true;

    // Relationships
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

    // You can add additional relationships if needed, for example:
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'merchant_id', 'merchant_id');
    }
}
