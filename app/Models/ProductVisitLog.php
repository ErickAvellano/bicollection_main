<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVisitLog extends Model
{
    use HasFactory;

    protected $table = 'product_visit_logs'; // Define the table name

    protected $fillable = ['product_id', 'view_count']; // Allow mass assignment of these fields

    public $timestamps = false; // Disable Laravel's default timestamps since we handle `updated_at`

    /**
     * Relationship with Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
