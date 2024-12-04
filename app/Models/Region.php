<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';

    protected $primaryKey = 'regions_id'; // Set primary key to custom name

    protected $fillable = [
        'name',
        'description',
        'products_list',
    ];

    // Optionally, you can add other model properties or methods as needed
}

