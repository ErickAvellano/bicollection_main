<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSupportGuide extends Model
{
    use HasFactory; 

    protected $table = 'customer_support_guide';
    protected $primaryKey = 'guide_id';
    public $timestamps = true; 
    
    protected $fillable = [
        'guide_title', 
        'category',
        'ratings', 
        'step_1', 'step_1_description', 'step_1_has_image',
        'step_2', 'step_2_description', 'step_2_has_image',
        'step_3', 'step_3_description', 'step_3_has_image',
        'step_4', 'step_4_description', 'step_4_has_image',
        'step_5', 'step_5_description', 'step_5_has_image',
        'step_6', 'step_6_description', 'step_6_has_image',
        'step_7', 'step_7_description', 'step_7_has_image',
        'step_8', 'step_8_description', 'step_8_has_image',
        'step_9', 'step_9_description', 'step_9_has_image',
        'step_10', 'step_10_description', 'step_10_has_image',
    ];
}
