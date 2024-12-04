<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'order';

    // Specify the primary key column (if different from 'id')
    protected $primaryKey = 'order_id';

    // Disable timestamps if not using 'created_at' or 'updated_at' columns
    public $timestamps = false;

    // Allow mass assignment for these columns
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

    /**
     * Define relationships with other models
     */

    // Relationship to the Customer model (assuming a Customer model exists)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Relationship to the Merchant model (assuming a Merchant model exists)
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    // Relationship to the MerchantMop model (assuming a MerchantMop model exists)
    public function merchantMop()
    {
        return $this->belongsTo(MerchantMop::class, 'merchant_mop_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    // Relationship to the Payment model
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    /**
     * Accessors and Mutators
     */

    // Accessor for formatted total amount
    public function getFormattedTotalAmountAttribute()
    {
        return '₱' . number_format($this->total_amount, 2);
    }

    // Accessor for formatted shipping fee
    public function getFormattedShippingFeeAttribute()
    {
        return '₱' . number_format($this->shipping_fee, 2);
    }

    // Mutator for setting order status to lowercase
    public function setOrderStatusAttribute($value)
    {
        $this->attributes['order_status'] = strtolower($value);
    }
    public function productReviews()
    {
        return $this->hasMany(ProductReview::class, 'order_id');
    }



}
