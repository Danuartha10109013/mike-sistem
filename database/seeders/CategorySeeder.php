<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Perangkat Lunak',
            'description' => 'Kategori untuk perangkat lunak',
        ]);

        Category::create([
            'name' => 'Perangkat Keras',
            'description' => 'Kategori untuk perangkat keras',
        ]);

        Category::create([
            'name' => 'Jaringan',
            'description' => 'Kategori untuk jaringan',
        ]);

        Category::create([
            'name' => 'Aset Digital',
            'description' => 'Kategori untuk aset digital',
        ]);
    }
}
