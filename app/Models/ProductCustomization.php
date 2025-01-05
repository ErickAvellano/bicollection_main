<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCustomization extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'product_customizations';

    // Primary key
    protected $primaryKey = 'product_customizations_id';

    public $timestamps = false; // Set to true if `updated_at` exists

    // Fillable columns for mass assignment
    protected $fillable = [
        'material',
        'rim_color',
        'body_color',
        'base_color',
        'pattern',
        'price',
        'customer_id',
        'merchant_id',
        'created_at',
    ];

    // Default attributes
    protected $attributes = [
        'pattern' => null, // Default value for pattern
    ];
}
