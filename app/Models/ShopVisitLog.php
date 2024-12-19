<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopVisitLog extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'shop_visit_logs';

    // Specify the fillable fields to allow mass assignment
    protected $fillable = [
        'shop_id',
        'click_count',
    ];

    /**
     * Define the relationship with the Shop model.
     * Assuming you have a Shop model and `shop_id` is a foreign key.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }

    /**
     * Scope to get visits for a specific shop.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $shopId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }

    /**
     * Increment the click count for the current record.
     */
    public function incrementClickCount()
    {
        $this->increment('click_count');
    }
}
