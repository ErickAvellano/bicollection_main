<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $table = 'customer_address';

    protected $primaryKey = 'customer_address_id';

    protected $keyType = 'int';

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
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
