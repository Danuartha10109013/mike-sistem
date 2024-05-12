<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::create([
            'name' => 'Ruang Rapat',
            'description' => 'Ruang rapat untuk rapat internal',
        ]);

        Room::create([
            'name' => 'Ruang Lantai 1',
            'description' => 'Ruang lantai 1 untuk rapat internal',
        ]);

        Room::create([
            'name' => 'Ruang Lantai 2',
            'description' => 'Ruang lantai 2 untuk rapat internal',
        ]);

        Room::create([
            'name' => 'Ruang Lantai 3',
            'description' => 'Ruang lantai 3 untuk rapat internal',
        ]);

    }
}
