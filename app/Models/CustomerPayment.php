<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'customer_payment';

    // Specify the primary key
    protected $primaryKey = 'customer_payment_id';

    // Enable auto-incrementing
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'account_type',
        'account_number',
    ];

    /**
     * Define a relationship with the Customer model.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
    public function payment()
    {
        return $this->hasOne(CustomerPayment::class, 'customer_id');
    }
    }
