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
                'psto_name' => 'Zamboanga Sibugay',
                'slug' => 'zamboanga-sibugay',
            ],
            [
                'psto_name' => 'Zamboanga del Sur',
                'slug' => 'zamboanga-del-sur',
            ],
            [
                'psto_name' => 'Zamboanga del Norte',
                'slug' => 'zamboanga-del-norte',
            ],
            [
                'psto_name' => 'Zamboanga City',
                'slug' => 'zamboanga-city',
            ],  
 
        ];

        psto::insert($sub_units);
    }
}
