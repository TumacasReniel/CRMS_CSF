<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubUnit;

class SubUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sub_units = [
            [
                'sub_unit_name' => 'Procurement',
                'slug' => 'procurement',
                'unit_id' => '7',
            ],
            [
                'sub_unit_name' => 'Property and Supply',
                'slug' => 'property-and-supply',
                'unit_id' => '7',
            ],
            [
                'sub_unit_name' => 'Driving',
                'slug' => 'driving',
                'unit_id' => '7',
            ],
            [
                'sub_unit_name' => 'Janitorial',
                'slug' => 'janitorial',
                'unit_id' => '7',
            ],
            [
                'sub_unit_name' => 'Maintenance',
                'slug' => 'maintenance',
                'unit_id' => '7',
            ],


            //
            [
                'sub_unit_name' => 'Training',
                'slug' => 'training',
                'unit_id' => '10',
            ],
            [
                'sub_unit_name' => 'Consultancy',
                'slug' => 'consultancy',
                'unit_id' => '10',
            ],
            [
                'sub_unit_name' => 'Manufacturing Productivity Extension Program',
                'slug' => 'manufacturing-productivity-extension-program',
                'unit_id' => '10',
            ],
            [
                'sub_unit_name' => 'Consultancy for Agricultural Productivity',
                'slug' => 'consultancy-for-agricultural-productivity',
                'unit_id' => '10',
            ],
            [
                'sub_unit_name' => 'S&T Experts Volunteer Pool Program',
                'slug' => 's&t-experts-volunteer-pool-program	',
                'unit_id' => '10',
            ],
            [
                'sub_unit_name' => 'DOST-Academe Technology Assessment',
                'slug' => 'dost-academe-technology-assessment',
                'unit_id' => '10',
            ],
            [
                'sub_unit_name' => 'Energy Audit',
                'slug' => 'energy-audit',
                'unit_id' => '10',
            ],
            [
                'sub_unit_name' => 'Packaging and Labeling',
                'slug' => 'packaging-and-labeling',
                'unit_id' => '10',
            ],
            [
                'sub_unit_name' => 'Food Safety',
                'slug' => 'food-safety',
                'unit_id' => '10',
            ],
            //
            [
                'sub_unit_name' => 'PCHRD',
                'slug' => 'pcrd',
                'unit_id' => '20',
            ],

            [
                'sub_unit_name' => 'PCIEERD',
                'slug' => 'pcieerd',
                'unit_id' => '20',
            ],

            [
                'sub_unit_name' => 'PCAARRD',
                'slug' => 'pcaard',
                'unit_id' => '20',
            ],

            [
                'sub_unit_name' => 'NSTEP',
                'slug' => 'nstep',
                'unit_id' => '20',
            ],


        ];

        SubUnit::insert($sub_units);
    }
}
