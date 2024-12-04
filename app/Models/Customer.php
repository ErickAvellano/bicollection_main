<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; 

class Customer extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'customer'; // Assuming the table is named 'customers'

    // Specify the primary key of the table
    protected $primaryKey = 'customer_id';  // Use customer_id as the primary key

    // Disable auto-incrementing as this is handled by the users table
    public $incrementing = false;

    // Define the key type
    protected $keyType = 'int';

    // Define the fillable attributes
    protected $fillable = [
        'customer_id',  // This should be the same as user_id from the users table
        'username',
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'gender',
    ];

    // Enable timestamps for the model
    public $timestamps = true;

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id'); // Reference the users table
    }

    // Define the relationship with the CustomerImage model
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
