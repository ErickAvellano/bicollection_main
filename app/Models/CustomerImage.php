<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerImage extends Model
{
    protected $table = 'customer_img';

    protected $primaryKey = 'customer_img_id';

    protected $fillable = ['customer_id', 'img_path'];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
}
