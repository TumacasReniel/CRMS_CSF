<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'SUPER ADMIN';
        $user->email = 'dost.ro9@gmail.com';
        $user->account_type = 'admin';
        $user->password = bcrypt('dost-superadmin');
        $user->region_id = 10;
        $user->save();

        $user = new User();
        $user->name = 'ORD PLANNING';
        $user->email = 'ord.planning@gmail.com';
        $user->account_type = 'planning';
        $user->password = bcrypt('dost-planning');
        $user->region_id = 10;
        $user->save();
    }
}
