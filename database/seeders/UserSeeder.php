<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Rizal',
            'email' => 'rizal@mail.com',
            'password' => bcrypt('123123'),
        ]);

        User::factory(10)->create();
    }
}
