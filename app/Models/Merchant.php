<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $table = 'merchant';

    protected $primaryKey = 'merchant_id';

    public $timestamps = true;


    protected $fillable = [
        'user_id',
        'username',
        'email',
        'firstname',
        'lastname',
        'contact_number',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function region()
    {
        return $this->belongsTo(Region::class); 
    }
    public function shops()
    {
        return $this->hasMany(Shop::class, 'merchant_id', 'merchant_id');
    }
}
