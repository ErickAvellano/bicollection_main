<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'customer_address';

    // Specify the primary key of the table
    protected $primaryKey = 'customer_address_id';

    // Define the key type
    protected $keyType = 'int';

    // Define the fillable attributes
    protected $fillable = [
        'customer_id',
        'house_street',
        'region',
        'province',
        'city',
        'barangay',
        'postalcode',
    ];
    public $timestamps = false;

    // Define the relationship with the Customer model
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
