<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'name' => 'Apple',
            'description' => 'Apple Inc. is an American multinational technology company that specializes in consumer electronics, computer software, and online services.'
        ]);
        Brand::create([
            'name' => 'Samsung',
            'description' => 'Samsung Electronics Co., Ltd. is a South Korean multinational electronics company headquartered in the Yeongtong District of Suwon.'
        ]);
        Brand::create([
            'name' => 'Huawei',
            'description' => 'Huawei Technologies Co., Ltd. is a Chinese multinational technology company headquartered in Shenzhen, Guangdong.'
        ]);
        Brand::create([
            'name' => 'Xiaomi',
            'description' => 'Xiaomi Corporation is a Chinese multinational electronics company founded in April 2010 and headquartered in Beijing.'
        ]);
    }
}
