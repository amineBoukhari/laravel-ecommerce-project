<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run()
    {
        DB::table('orders')->insert([
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
                'tracking_id' => 'TRK123456',
                'delivery_status' => 'processing',
                'payment_status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
