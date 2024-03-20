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
                'title' => 'Procurement',
                'slug' => 'procurement',
                'unit_id' => 7,
            ],
            [
                'title' => 'Property and Supply',
                'slug' => 'property-and-supply',
                'unit_id' => 7,
            ],
            [
                'title' => 'Driving',
                'slug' => 'driving',
                'unit_id' => 7,
            ],
            [
                'title' => 'Janitorial',
                'slug' => 'janitorial',
                'unit_id' => 7,
            ],
            [
                'title' => 'Maintenance',
                'slug' => 'maintenance',
                'unit_id' => 7,
            ],


            //
            [
                'title' => 'Training',
                'slug' => 'training',
                'unit_id' => 10,
            ],
            [
                'title' => 'Consultancy',
                'slug' => 'consultancy',
                'unit_id' => 10,
            ],
            [
                'title' => 'Manufacturing Productivity Extension Program',
                'slug' => 'manufacturing-productivity-extension-program',
                'unit_id' => 10,
            ],
            [
                'title' => 'Consultancy for Agricultural Productivity',
                'slug' => 'consultancy-for-agricultural-productivity',
                'unit_id' => 10,
            ],
            [
                'title' => 'S&T Experts Volunteer Pool Program',
                'slug' => 's&t-experts-volunteer-pool-program	',
                'unit_id' => 10,
            ],
            [
                'title' => 'DOST-Academe Technology Assessment',
                'slug' => 'dost-academe-technology-assessment',
                'unit_id' => 10,
            ],
            [
                'title' => 'Energy Audit',
                'slug' => 'energy-audit',
                'unit_id' => 10,
            ],
            [
                'title' => 'Packaging and Labeling',
                'slug' => 'packaging-and-labeling',
                'unit_id' => 10,
            ],
            [
                'title' => 'Food Safety',
                'slug' => 'food-safety',
                'unit_id' => 10,
            ],
            //
            [
                'title' => 'PCHRD',
                'slug' => 'pcrd',
                'unit_id' => 20,
            ],

            [
                'title' => 'PCIEERD',
                'slug' => 'pcieerd',
                'unit_id' => 20,
            ],

            [
                'title' => 'PCAARRD',
                'slug' => 'pcaard',
                'unit_id' => 20,
            ],

            [
                'title' => 'NSTEP',
                'slug' => 'nstep',
                'unit_id' => 20,
            ],


        ];

        SubUnit::insert($sub_units);
    }
}
