<?php

namespace Database\Seeders;

use App\Models\SubUnitPsto;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubUnitPSTOSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unit_pstos = [
            [
                'sub_unit_id' => 6,
                'psto_id' => 1,
            ],
            [
                'sub_unit_id' => 6,
                'psto_id' => 2,
            ],
            [
                'sub_unit_id' => 6,
                'psto_id' => 3,
            ],
            [
                'sub_unit_id' => 6,
                'psto_id' => 4,
            ],  
            //
            [
                'sub_unit_id' => 7,
                'psto_id' => 1,
            ],  
            [
                'sub_unit_id' => 7,
                'psto_id' => 2,
            ],  
            [
                'sub_unit_id' => 7,
                'psto_id' => 3,
            ],  
            [
                'sub_unit_id' => 7,
                'psto_id' => 4,
            ],  

            //
            [
                'sub_unit_id' => 8,
                'psto_id' => 1,
            ],  
            [
                'sub_unit_id' => 8,
                'psto_id' => 2,
            ],  
            [
                'sub_unit_id' => 8,
                'psto_id' => 3,
            ],  
            [
                'sub_unit_id' => 8,
                'psto_id' => 4,
            ],  
            //
            [
                'sub_unit_id' => 9,
                'psto_id' => 1,
            ],  
            [
                'sub_unit_id' => 9,
                'psto_id' => 2,
            ],  
            [
                'sub_unit_id' => 9,
                'psto_id' => 3,
            ],  
            [
                'sub_unit_id' => 9,
                'psto_id' => 4,
            ],  
             //
             [
                'sub_unit_id' => 10,
                'psto_id' => 1,
            ],  
            [
                'sub_unit_id' => 10,
                'psto_id' => 2,
            ],  
            [
                'sub_unit_id' => 10,
                'psto_id' => 3,
            ],  
            [
                'sub_unit_id' => 10,
                'psto_id' => 4,
            ],  
            //
            [
                'sub_unit_id' => 11,
                'psto_id' => 1,
            ],  
            [
                'sub_unit_id' => 11,
                'psto_id' => 2,
            ],  
            [
                'sub_unit_id' => 11,
                'psto_id' => 3,
            ],  
            [
                'sub_unit_id' => 11,
                'psto_id' => 4,
            ],  
             //
             [
                'sub_unit_id' => 12,
                'psto_id' => 1,
            ],  
            [
                'sub_unit_id' => 12,
                'psto_id' => 2,
            ],  
            [
                'sub_unit_id' => 12,
                'psto_id' => 3,
            ],  
            [
                'sub_unit_id' => 12,
                'psto_id' => 4,
            ], 
             //
             [
                'sub_unit_id' => 13,
                'psto_id' => 1,
            ],  
            [
                'sub_unit_id' => 13,
                'psto_id' => 2,
            ],  
            [
                'sub_unit_id' => 13,
                'psto_id' => 3,
            ],  
            [
                'sub_unit_id' => 13,
                'psto_id' => 4,
            ], 
            //
            [
                'sub_unit_id' => 14,
                'psto_id' => 1,
            ],  
            [
                'sub_unit_id' => 14,
                'psto_id' => 2,
            ],  
            [
                'sub_unit_id' => 14,
                'psto_id' => 3,
            ],  
            [
                'sub_unit_id' => 14,
                'psto_id' => 4,
            ], 

        ];

        SubUnitPsto::insert($unit_pstos);
    }
}
