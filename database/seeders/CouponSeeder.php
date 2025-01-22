<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        Coupon::create([
            'code' => 'DISCOUNT10',
            'discount_percentage' => 10,
            'min_order_amount' => 50, // Minimum cart total to apply the coupon
            'expires_at' => now()->addDays(30), // Expiry date 30 days from now
        ]);

        Coupon::create([
            'code' => 'SUMMER20',
            'discount_percentage' => 20,
            'min_order_amount' => 100,
            'expires_at' => now()->addDays(60),
        ]);

        Coupon::create([
            'code' => 'WELCOME5',
            'discount_percentage' => 5,
            'min_order_amount' => 20,
            'expires_at' => now()->addDays(90),
        ]);
    }
}
