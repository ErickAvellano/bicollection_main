<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CustomerSupportGuide extends Model
{
    use HasFactory;

    // Define the table name (optional if the table name follows Laravel's naming conventions)
    protected $table = 'customer_support_guides';

    // Define the primary key (optional if the primary key is "id")
    protected $primaryKey = 'guide_id';
    public $timestamps = true; 
    // Specify the fields that are mass assignable
    protected $fillable = [
        'guide_title',
        'step_1', 'step_1_description',
        'step_2', 'step_2_description',
        'step_3', 'step_3_description',
        'step_4', 'step_4_description',
        'step_5', 'step_5_description',
        'step_6', 'step_6_description',
        'step_7', 'step_7_description',
        'step_8', 'step_8_description',
        'step_9', 'step_9_description',
        'step_10', 'step_10_description',
    ];
}
