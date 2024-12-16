<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $primaryKey = 'order_id';

    public $timestamps = false;

    protected $fillable = [
        'cart_id',
        'customer_id',
        'merchant_id',
        'merchant_mop_id',
        'total_amount',
        'shipping_fee',
        'order_status',
        'shipping_address',
        'contact_number',
        'created_at',
        'updated_at'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public function merchantMop()
    {
        return $this->belongsTo(MerchantMop::class, 'merchant_mop_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    public function getFormattedTotalAmountAttribute()
    {
        return '₱' . number_format($this->total_amount, 2);
    }

    public function getFormattedShippingFeeAttribute()
    {
        return '₱' . number_format($this->shipping_fee, 2);
    }

    public function setOrderStatusAttribute($value)
    {
        $this->attributes['order_status'] = strtolower($value);
    }
    public function productReviews()
    {
        return $this->hasMany(ProductReview::class, 'order_id');
    }



}
