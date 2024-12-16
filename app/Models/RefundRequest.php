<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;

    protected $table = 'refund_request';

    protected $primaryKey = 'refund_id';

    public $timestamps = true;

    protected $fillable = [
        'payment_id',
        'order_id',
        'refund_status',
    ];


    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
