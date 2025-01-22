<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    public function run()
    {
        DB::table('carts')->insert([
            [
                'user_id' => 1,
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'phone' => '1234567890',
                'address' => '123 Admin Street',
                'product_title' => 'Gaming Laptop',
                'product_id' => 1,
                'price' => '1100',
                'quantity' => '1',
                'image' => 'laptop1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
