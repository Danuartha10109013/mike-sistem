<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $categories = Category::get();
        $brands = Brand::get();
        $rooms = Room::get();
        $users = User::get();

        for ($i = 0; $i < 10; $i++) {
            Asset::create([
                'number' => 'ASSET-' . date('Ymd') . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'name' => $faker->randomElement(['Laptop '.$i + 1, 'Printer '.$i + 1]),
                'quantity' => 1,
                'brand_id' => 2,
                'category_id' => $categories->random()->id,
                'room_id' => $rooms->random()->id,
                'user_id' => $i +3,
                'decrease' => $faker->numberBetween(1, 10),
                'price' => $faker->numberBetween(1, 5) * 1000000,
                'date' => $faker->dateTimeBetween('-1 year', 'now'),
                'condition' => $faker->randomElement(['new', 'used', 'damaged']),
                'created_at' => now()->addMinutes($i),
            ]);
        }
    }
}
