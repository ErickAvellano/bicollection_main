<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'shop';

    // The primary key associated with the table.
    protected $primaryKey = 'shop_id';

    // Indicates if the model should be timestamped.
    public $timestamps = true;

    // The attributes that are mass assignable.
    protected $fillable = [
        'merchant_id',
        'shop_name',
        'description',
        'shop_img',
        'coverphotopath',
        'shop_street',
        'province',
        'city',
        'barangay',
        'postal_code', 
        'created_at',
        'registration_step',
        'terms_accepted',
        'verification_status',
    ];

    // The attributes that should be cast to native types.
    protected $casts = [
        'created_at' => 'datetime',
        'terms_accepted' => 'boolean',
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'merchant_id', 'user_id');
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'merchant_id', 'merchant_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'merchant_id', 'merchant_id');
    }
    public function applications()
    {
        return $this->hasMany(Application::class, 'shop_id', 'shop_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'merchant_id');
    }
    // Define other relationships if necessary, e.g., for products, orders, etc.
}
