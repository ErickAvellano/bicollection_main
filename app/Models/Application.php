<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'application';

    // Define the primary key for the table
    protected $primaryKey = 'application_id';

    // If the primary key is not an incrementing integer, set this to false
    public $incrementing = true;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'merchant_id',
        'shop_id',
        'shop_name',
        'dti_cert_path',
        'mayors_permit_path',
        'about_store',
        'categories',
        'shop_street',
        'province',
        'city',
        'barangay',
        'postal_code',
    ];

    // Define the attributes that should be cast to native types
    protected $casts = [
        'created_at' => 'datetime',
    ];
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }

    // Define the relationship to the Merchant model
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'merchant_id');
    }
}
