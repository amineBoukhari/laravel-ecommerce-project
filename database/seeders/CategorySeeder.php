<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['category_name' => 'Laptops', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Phones', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Accessories', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
