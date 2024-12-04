<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'merchant';

    // The primary key associated with the table.
    protected $primaryKey = 'merchant_id';

    // Disable timestamps if you're not using created_at and updated_at
    public $timestamps = true;

    // Define the fillable attributes (these fields can be mass-assigned)
    protected $fillable = [
        'user_id',
        'username',
        'email',
        'firstname',
        'lastname',
        'contact_number',
    ];

    // Define the relationship with the User model (assuming a User has one Merchant)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function region()
    {
        return $this->belongsTo(Region::class); // Assuming there's a Region model
    }
    public function shops()
    {
        return $this->hasMany(Shop::class, 'merchant_id', 'merchant_id');
    }
}
