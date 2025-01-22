<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Fetch orders for the logged-in user
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        // Return view with orders
        return view('orders.index', compact('orders'));
    }
}
