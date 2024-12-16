<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    protected $table = 'category';
    
    protected $primaryKey = 'category_id'; 

    public $incrementing = true; 

    protected $keyType = 'int';

    
    public $timestamps = true;

    protected $fillable = [
        'category_name',
        'category_description',
        'parentcategoryID',
        'created_at'
    ];


    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parentcategoryID', 'category_id');
    }

   
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parentcategoryID', 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
