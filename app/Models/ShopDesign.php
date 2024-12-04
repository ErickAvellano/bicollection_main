<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopDesign extends Model
{
    use HasFactory;

    protected $table = 'shop_design';
    protected $primaryKey = 'shop_design_id';
    public $timestamps = false;
    protected $fillable = [
        'shop_id',
        'featuredProduct',
        'display1',
        'display2',
    ];
}
