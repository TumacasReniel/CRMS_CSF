<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'services_id' => 1,
                'unit_name' => 'Secretariat',
            ],
            [
                'services_id' => 1,
                'unit_name' => 'Gender and Development(GAD)',
            ],
            [
                'services_id' => 1,
                'unit_name' => 'Fairness Opinion Board',
            ],

            [
                'services_id' => 2,
                'unit_name' => 'Accounting',
            ],
            [
                'services_id' => 2,
                'unit_name' => 'Budgeting',
            ],
            [
                'services_id' => 2,
                'unit_name' => 'Cashiering',
            ],
            [
                'services_id' => 2,
                'unit_name' => 'Administrative Support Services',
            ],
            [
                'services_id' => 2,
                'unit_name' => 'Human Resource',
            ],

            [
                'services_id' => 3,
                'unit_name' => 'Innovation System Support',
            ],
            [
                'services_id' => 3,
                'unit_name' => 'Technology Training and Consultancy',
            ],
            [
                'services_id' => 3,
                'unit_name' => 'S&T Intervention',
            ],
            [
                'services_id' => 3,
                'unit_name' => 'RPMO',
            ],

            [
                'services_id' => 3,
                'unit_name' => 'RSTL',
            ],
            [
                'services_id' => 3,
                'unit_name' => 'S&T Scholarship',
            ],
            [
                'services_id' => 3,
                'unit_name' => 'S&T Information',
            ],
            [
                'services_id' => 3,
                'unit_name' => 'DRRM',
            ],
            [
                'services_id' => 3,
                'unit_name' => 'Management Information Sytem',
            ],
            [
                'services_id' => 3,
                'unit_name' => 'HALAL',
            ],
            [
                'services_id' => 3,
                'unit_name' => 'RxBox',
            ],
            [
                'services_id' => 3,
                'unit_name' => 'Research and Development Support',
            ],
            [
                'services_id' => 3,
                'unit_name' => 'Innovation Support Services',
            ],

        ];

        Unit::insert($units);
    }
}
