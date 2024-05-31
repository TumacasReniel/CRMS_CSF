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
                'id' => 1,
                'name' => 'REGION I',
                'code' => 'ro1',
                'short_name' => 'REGION-I',
                'order' => '1',
            ],

            [
                'id' => 2,
                'name' => 'REGION II',
                'code' => 'ro2',
                'short_name' => 'REGION-II',
                'order' => '2',
            ],

            [
                'id' => 3,
                'name' => 'REGION III',
                'code' => 'ro3',
                'short_name' => 'REGION-III',
                'order' => '3',
            ],

            [
                'id' => 4,
                'name' => 'REGION IVA',
                'code' => 'ro4A',
                'short_name' => 'REGION-IVA',
                'order' => '4.1',
            ],

            [
                'id' => 5,
                'name' => 'REGION IVB',
                'code' => 'ro4B',
                'short_name' => 'REGION-IVB',
                'order' => '4.2',
            ],

            [
                'id' => 6,
                'name' => 'REGION V',
                'code' => 'ro5',
                'short_name' => 'REGION-V',
                'order' => '5',
            ],

            [
                'id' => 7,
                'name' => 'REGION VI',
                'code' => 'ro6',
                'short_name' => 'REGION-VI',
                'order' => '6',
            ],

            [
                'id' => 8,
                'name' => 'REGION VII',
                'code' => 'ro7',
                'short_name' => 'REGION-VII',
                'order' => '7',
            ],

            [
                'id' => 9,
                'name' => 'REGION VIII',
                'code' => 'ro8',
                'short_name' => 'REGION-VIII',
                'order' => '8',
            ],

            [
                'id' => 10,
                'name' => 'REGION IX',
                'code' => 'ro9',
                'short_name' => 'REGION-IX',
                'order' => '9',
            ],

            [
                'id' => 11,
                'name' => 'REGION X',
                'code' => 'ro10',
                'short_name' => 'REGION-X',
                'order' => '10',
            ],

            [
                'id' => 12,
                'name' => 'REGION XI',
                'code' => 'ro11',
                'short_name' => 'REGION-XI',
                'order' => '11',
            ],

            [
                'id' => 13,
                'name' => 'REGION XII',
                'code' => 'ro12',
                'short_name' => 'REGION-XII',
                'order' => '12',
            ],

            
            [
                'id' => 14,
                'name' => 'REGION ARMM',
                'code' => 'ro13',
                'short_name' => 'REGION-ARMM',
                'order' => '13',
            ],

            [
                'id' => 15,
                'name' => 'REGION CAR',
                'code' => 'ro14',
                'short_name' => 'REGION-CAR',
                'order' => '14',
            ],

            
            [
                'id' => 16,
                'name' => 'REGION CARAGA',
                'code' => 'ro15',
                'short_name' => 'REGION-CARAGA',
                'order' => '15',
            ],

            [
                'id' => 17,
                'name' => 'REGION NCR',
                'code' => 'ro16',
                'short_name' => 'REGION-NCR',
                'order' => '16',
            ],

            
            [
                'id' => 18,
                'name' => 'REGION ROS',
                'code' => 'ro17',
                'short_name' => 'REGION-ROS',
                'order' => '17',
            ],

        ];

        Region::insert($regions);
    }
}
