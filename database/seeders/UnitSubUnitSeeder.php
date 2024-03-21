<?php

namespace Database\Seeders;

use App\Models\UnitSubUnit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSubUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unit_sub_units = [
            [
                'unit_id' => 7,
                'sub_unit_id' => 1,
            ],
            [
                'unit_id' => 7,
                'sub_unit_id' => 2,
            ],
            [
                'unit_id' => 7,
                'sub_unit_id' => 3,
            ],
            [
                'unit_id' => 7,
                'sub_unit_id' => 4,
            ],  
            [
                'unit_id' => 7,
                'sub_unit_id' => 5,
            ],  

            //
            [
                'unit_id' => 10,
                'sub_unit_id' => 6,
            ],  
            [
                'unit_id' => 10,
                'sub_unit_id' => 7,
            ],  
            [
                'unit_id' => 10,
                'sub_unit_id' => 8,
            ],  
            [
                'unit_id' => 10,
                'sub_unit_id' => 9,
            ],  
            [
                'unit_id' => 10,
                'sub_unit_id' => 10,
            ],  
            [
                'unit_id' => 10,
                'sub_unit_id' => 11,
            ],  
            [
                'unit_id' => 10,
                'sub_unit_id' => 12,
            ],  
            [
                'unit_id' => 10,
                'sub_unit_id' => 13,
            ],  
            [
                'unit_id' => 10,
                'sub_unit_id' => 14,
            ],  
            //
            [
                'unit_id' => 20,
                'sub_unit_id' => 15,
            ],  
            [
                'unit_id' => 20,
                'sub_unit_id' => 16,
            ],  
            [
                'unit_id' => 20,
                'sub_unit_id' => 17,
            ],  
            [
                'unit_id' => 20,
                'sub_unit_id' => 18,
            ],  
 
 
 
        ];

        UnitSubUnit::insert($unit_sub_units);
    }
}
