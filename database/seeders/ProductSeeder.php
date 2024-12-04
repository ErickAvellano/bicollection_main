<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            'merchant_id' => 1,
            'product_name' => 'Sample Product',
            'quantity_item' => 100,
            'price' => '19.99',
            'description' => 'This is a sample product.',
            'category_id' => 1,
            'subcategory_id' => 1,
            'product_status' => 'Available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
