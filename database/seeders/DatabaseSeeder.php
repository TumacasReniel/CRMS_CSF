<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(RegionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(DimensionSeeder::class);
        $this->call(CcQuestionSeeder::class);
        $this->call(ServicesSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(SubUnitSeeder::class);
        $this->call(PSTOSeeder::class);
        $this->call(UnitPSTOSeeder::class);
        $this->call(SubUnitPSTOSeeder::class);
        $this->call(SubUnitTypeSeeder::class);
     
    }

    
}
