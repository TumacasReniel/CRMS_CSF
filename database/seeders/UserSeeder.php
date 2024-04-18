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
        $user->email = 'dost.ro9@gamil.com';
        $user->account_type = 'admin';
        $user->password = bcrypt('dost-superadmin');
        $user->region_id = 9;
        $user->save();
    }
}
