<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'payment';

    // Define the primary key
    protected $primaryKey = 'payment_id';

    // Specify the fields that can be mass assigned
    protected $fillable = [
        'order_id',
        'customer_id',
        'customer_payment_id',
        'payment_method',
        'amount',
        'receipt_img',
        'shipping_fee',
        'order_status',
        'payment_status',
    ];

    // Indicate if the model should manage timestamps
    public $timestamps = false; // Since you have 'created_at' but no 'updated_at'

    // Define custom timestamp column names if needed
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    /**
     * Define a relationship to the Order model.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Define a relationship to the Customer model.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Scope a query to only include payments of a given status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Set the payment status to 'Verified'.
     *
     * @return void
     */
    public function verify()
    {
        $this->payment_status = 'Verified';
        $this->save();
    }

    /**
     * Set the payment status to 'Denied'.
     *
     * @return void
     */
    public function deny()
    {
        $this->payment_status = 'Denied';
        $this->save();
    }

    /**
     * Get the full URL of the receipt image.
     *
     * @return string|null
     */
    public function getReceiptImageUrlAttribute()
    {
        return $this->receipt_img ? asset('storage/' . $this->receipt_img) : null;
    }

}
