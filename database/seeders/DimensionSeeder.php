<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dimension;

class DimensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dimensions = [
            [
                'name' => 'Responsiveness',
                'description' => '',
                'slug' => 'responsiveness',
                'is_active' => 1,
            ],
            [
                'name' => 'Reliability',
                'description' => '',
                'slug' => 'reliability',
                'is_active' => 1,
            ],
            [
                'name' => 'Access & Facilities',
                'description' => '',
                'slug' => 'access-and-facilities',
                'is_active' => 1,
            ],
            [
                'name' => 'Communication',
                'description' => '',
                'slug' => 'communication',
                'is_active' => 1,
            ],
            [
                'name' => 'Integrity',
                'description' => '',
                'slug' => 'integrity',
                'is_active' => 1,
            ],
            [
                'name' => 'Assurance',
                'description' => '',
                'slug' => 'assurance',
                'is_active' => 1,
            ],
            [
                'name' => 'Outcome',
                'description' => '',
                'slug' => 'outcome',
                'is_active' => 1,
            ],
        ];

        Dimension::insert($dimensions);
    }
}
