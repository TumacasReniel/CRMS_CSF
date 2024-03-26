<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            [
                'id' => 9,
                'name' => 'DOST IX',
                'code' => 'ro9',
                'short_name' => 'DOST-IX',
                'order' => '9',
            ],

        ];

        Region::insert($regions);
    }
}
