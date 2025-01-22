<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json(['message' => 'Invalid or expired coupon'], 400);
        }

        return response()->json([
            'message' => 'Coupon applied!',
            'discount_percentage' => $coupon->discount_percentage
        ], 200);
    }



    public function index()
    {
        // Fetch all coupons
        $coupons = Coupon::all();
        return view('admin.coupons', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'code' => 'required|string|unique:coupons',
            'discount_percentage' => 'required|numeric',
            'min_order_amount' => 'required|numeric',
        ]);

        // Store the coupon
        Coupon::create([
            'code' => $request->code,
            'discount_percentage' => $request->discount_percentage,
            'min_order_amount' => $request->min_order_amount,
        ]);

        // Redirect back with success message
        return redirect()->route('admin.coupons')->with('success', 'Coupon created successfully');
    }

    public function destroy($id)
    {
        // Find and delete the coupon
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        // Redirect back with success message
        return redirect()->route('admin.coupons')->with('success', 'Coupon deleted successfully');
    }
}