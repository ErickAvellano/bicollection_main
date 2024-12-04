<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regions')->insert([
            [
                'regions_id' => 1,
                'name' => 'Camarines Norte',
                'description' => 'Camarines Norte is a province in the Bicol Region of Luzon in the Philippines. It is known for its beautiful beaches and historical sites.',
                'products_list' => json_encode([
                    'Bicol Express',
                    'Coconut Wine',
                    'Handwoven Mats'
                ]),
            ],
            [
                'regions_id' => 2,
                'name' => 'Camarines Sur',
                'description' => 'Camarines Sur is a province in the Bicol Region, famous for its natural attractions including Lake Buhi and Caramoan Islands.',
                'products_list' => json_encode([
                    'Pili Nuts',
                    'Sili Ice Cream',
                    'Handicrafts'
                ]),
            ],
            [
                'regions_id' => 3,
                'name' => 'Albay',
                'description' => 'Albay is a province in the Bicol Region, known for its active volcano, Mayon, and rich cultural heritage.',
                'products_list' => json_encode([
                    'Bicol Express',
                    'Pinangat',
                    'Handwoven Fabrics'
                ]),
            ],
            [
                'regions_id' => 4,
                'name' => 'Sorsogon',
                'description' => 'Sorsogon is a province located in the southeastern tip of Luzon. It is known for its stunning natural beauty and whale shark watching.',
                'products_list' => json_encode([
                    'Sorsogon Barbecue',
                    'Handmade Pottery',
                    'Coconut Products'
                ]),
            ],
            [
                'regions_id' => 5,
                'name' => 'Catanduanes',
                'description' => 'Catanduanes is an island province in the Bicol Region, known for its rugged landscapes and beautiful beaches.',
                'products_list' => json_encode([
                    'Dried Fish',
                    'Handmade Crafts',
                    'Local Wines'
                ]),
            ],
            [
                'regions_id' => 6,
                'name' => 'Masbate',
                'description' => 'Masbate is known for its cattle ranching and the annual rodeo festival. It is a province with rich agricultural lands and beautiful landscapes.',
                'products_list' => json_encode([
                    'Masbate Beef',
                    'Local Baskets',
                    'Cultural Artifacts'
                ]),
            ],
        ]);
    }
}
