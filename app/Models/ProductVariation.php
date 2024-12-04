<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $table = 'product_variation'; // Specify the table name if it's not the plural of the model name
    protected $primaryKey = 'product_variation_id'; // Specify the primary key
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'variation_name',
        'variation_image',
        'quantity_item',
        'price',
        'product_status',
    ];

    // Define relationship
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
