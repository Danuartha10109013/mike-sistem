<?php

namespace Database\Seeders;

use App\Models\User;
use App\UserRole;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Director',
                'username' => 'direktur',
                'password' => bcrypt('123123'),
                'role' => UserRole::Admin
            ],[
                'name' => 'Admin',
                'username' => 'admin',
                'password' => bcrypt('123123'),
                'role' => UserRole::User
            ]
        ]);

        User::factory(10)->create();
    }
}
