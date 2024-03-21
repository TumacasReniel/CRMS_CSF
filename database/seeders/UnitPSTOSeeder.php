<?php

namespace Database\Seeders;

use App\Models\UnitPsto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitPSTOSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unit_pstos = [
            [
                'unit_id' => 9,
                'psto_id' => 1,
            ],
            [
                'unit_id' => 9,
                'psto_id' => 2,
            ],
            [
                'unit_id' => 9,
                'psto_id' => 3,
            ],
            [
                'unit_id' => 9,
                'psto_id' => 4,
            ],  
            //
            [
                'unit_id' => 11,
                'psto_id' => 1,
            ],  
            [
                'unit_id' => 11,
                'psto_id' => 2,
            ],  
            [
                'unit_id' => 11,
                'psto_id' => 3,
            ],  
            [
                'unit_id' => 11,
                'psto_id' => 4,
            ],  

            //
            [
                'unit_id' => 14,
                'psto_id' => 1,
            ],  
            [
                'unit_id' => 14,
                'psto_id' => 2,
            ],  
            [
                'unit_id' => 14,
                'psto_id' => 3,
            ],  
            [
                'unit_id' => 14,
                'psto_id' => 4,
            ],  
            //
            [
                'unit_id' => 15,
                'psto_id' => 1,
            ],  
            [
                'unit_id' => 15,
                'psto_id' => 2,
            ],  
            [
                'unit_id' => 15,
                'psto_id' => 3,
            ],  
            [
                'unit_id' => 15,
                'psto_id' => 4,
            ],  
            //
            [
                'unit_id' => 20,
                'psto_id' => 1,
            ],  
            [
                'unit_id' => 20,
                'psto_id' => 2,
            ],  
            [
                'unit_id' => 20,
                'psto_id' => 3,
            ],  
            [
                'unit_id' => 20,
                'psto_id' => 4,
            ],  

        ];

        UnitPsto::insert($unit_pstos);
    }
}
