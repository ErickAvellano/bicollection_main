<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;

    // Specify the table name if it doesn’t follow Laravel’s naming convention
    protected $table = 'refund_request';

    // Primary key
    protected $primaryKey = 'refund_id';

    // Disable timestamps if not using created_at and updated_at columns
    public $timestamps = true;

    // Specify which attributes can be mass-assigned
    protected $fillable = [
        'payment_id',
        'order_id',
        'refund_status',
    ];

    // Define the relationships

    /**
     * Get the payment associated with the refund request.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    /**
     * Get the order associated with the refund request.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
