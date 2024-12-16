<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $table = 'product_variation'; 
    protected $primaryKey = 'product_variation_id'; 
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'variation_name',
        'variation_image',
        'quantity_item',
        'price',
        'product_status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
