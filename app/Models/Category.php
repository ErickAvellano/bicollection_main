<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // If your table name doesn't follow Laravel's naming convention (i.e., it's not 'categories')
    protected $table = 'category';
    
    protected $primaryKey = 'category_id'; // Define the actual primary key

    public $incrementing = true; // Specify if the primary key is auto-incrementing

    protected $keyType = 'int'; // Specify the type of the primary key

    // Optional: Disable timestamps if they are not used
    public $timestamps = true;

    // The fields that are mass assignable
    protected $fillable = [
        'category_name',
        'category_description',
        'parentcategoryID',
        'created_at'
    ];

    // This defines a relationship where a category may have subcategories
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parentcategoryID', 'category_id');
    }

    // This defines the inverse relationship, where a category may be a subcategory of another category
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parentcategoryID', 'category_id');
    }

    // You may also have a relationship to products, depending on how your database is structured
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
