<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImg extends Model
{
    use HasFactory;
    protected $table = 'product_img'; // Specify the table name if it's not the plural of the model name
    protected $primaryKey = 'product_img_id'; // Specify the primary key
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'product_img_path1',
        'product_img_path2',
        'product_img_path3',
        'product_img_path4',
        'product_img_path5',
    ];

    // Define relationship
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
