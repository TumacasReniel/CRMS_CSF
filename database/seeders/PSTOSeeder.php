<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\psto;

class PSTOSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sub_units = [
            [
                'name' => 'Zamboanga Sibugay',
                'slug' => 'zamboanga-sibugay',
                'unit_id' => 9,
            ],
            [
                'title' => 'Zamboanga del Sur',
                'slug' => 'zamboanga-del-sur',
                'unit_id' => 9,
            ],
            [
                'title' => 'Zamboanga del Norte',
                'slug' => 'zamboanga-del-norte',
                'unit_id' => 9,
            ],
            [
                'title' => 'Zamboanga City',
                'slug' => 'zamboanga-city',
                'unit_id' => 9,
            ],
 
        ];

        psto::insert($sub_units);
    }
}
