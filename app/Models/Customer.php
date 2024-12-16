<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; 

class Customer extends Model
{
    use HasFactory;


    protected $table = 'customer'; 

    
    protected $primaryKey = 'customer_id';  

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'customer_id',  
        'username',
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'gender',
    ];


    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id'); // Reference the users table
    }

    public function customerImage()
    {
        return $this->hasOne(CustomerImage::class, 'customer_id', 'customer_id');
    }
    public function addresses()
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id', 'customer_id');
    }
    public function payment()
    {
        return $this->hasOne(CustomerPayment::class, 'customer_id', 'customer_id');
    }
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
