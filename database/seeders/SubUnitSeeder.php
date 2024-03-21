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
            ],
            [
                'sub_unit_name' => 'Property and Supply',
                'slug' => 'property-and-supply',
            ],
            [
                'sub_unit_name' => 'Driving',
                'slug' => 'driving',
            ],
            [
                'sub_unit_name' => 'Janitorial',
                'slug' => 'janitorial',
            ],
            [
                'sub_unit_name' => 'Maintenance',
                'slug' => 'maintenance',
            ],


            //
            [
                'sub_unit_name' => 'Training',
                'slug' => 'training',
            ],
            [
                'sub_unit_name' => 'Consultancy',
                'slug' => 'consultancy',
            ],
            [
                'sub_unit_name' => 'Manufacturing Productivity Extension Program',
                'slug' => 'manufacturing-productivity-extension-program',
            ],
            [
                'sub_unit_name' => 'Consultancy for Agricultural Productivity',
                'slug' => 'consultancy-for-agricultural-productivity',
            ],
            [
                'sub_unit_name' => 'S&T Experts Volunteer Pool Program',
                'slug' => 's&t-experts-volunteer-pool-program	',
            ],
            [
                'sub_unit_name' => 'DOST-Academe Technology Assessment',
                'slug' => 'dost-academe-technology-assessment',
            ],
            [
                'sub_unit_name' => 'Energy Audit',
                'slug' => 'energy-audit',
            ],
            [
                'sub_unit_name' => 'Packaging and Labeling',
                'slug' => 'packaging-and-labeling',
            ],
            [
                'sub_unit_name' => 'Food Safety',
                'slug' => 'food-safety',
            ],
            //
            [
                'sub_unit_name' => 'PCHRD',
                'slug' => 'pcrd',
            ],

            [
                'sub_unit_name' => 'PCIEERD',
                'slug' => 'pcieerd',
            ],

            [
                'sub_unit_name' => 'PCAARRD',
                'slug' => 'pcaard',
            ],

            [
                'sub_unit_name' => 'NSTEP',
                'slug' => 'nstep',
            ],


        ];

        SubUnit::insert($sub_units);
    }
}
