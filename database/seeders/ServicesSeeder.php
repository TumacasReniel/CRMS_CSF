<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Services;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'services_name' => 'OFFICE OF THE REGIONAL DIRECTOR',
                'slug' => 'office-of-theregional-director',
            ],
            [
                'services_name' => 'FINANCE AND ADMINISTRATIVE SUPPORT SERVICES',
                'slug' => 'finance-and-administrative-support-services',
            ],
            [
                'services_name' => 'TECHNICAL OPERATION SERVICES',
                'slug' => 'technical-operation-services',
            ],
        ];

        Services::insert($services);
    }
}
