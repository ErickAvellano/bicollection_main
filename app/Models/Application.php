<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $table = 'application';
    protected $primaryKey = 'application_id';
    public $incrementing = true;
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


    protected $casts = [
        'created_at' => 'datetime',
    ];
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'merchant_id');
    }
}
