<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Stripe;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Http;



class HomeController extends Controller
{
    //
    public function index(Request $request)
{
    // Get all categories
    $categories = Category::all();

    // Retrieve the sort parameter from the request (default to 'asc')

    // Retrieve the category filter parameter (if any)
    $categoryFilter = $request->get('category', null); // 'null' if no category filter
    $sort= $request->get('sort', null); // 'null' if no category filter

    // Start building the query to fetch products
    $query = Product::query();

    // Apply category filter if provided
    if ($categoryFilter) {
        $query->where('category', $categoryFilter);
    }

    
    if ($sort === 'asc') {
        // Sorting in ascending order
        $query->orderByRaw('CAST(discount_price AS DECIMAL(10,2)) ASC');
    } elseif ($sort === 'desc') {
        // Sorting in descending order
        $query->orderByRaw('CAST(discount_price AS DECIMAL(10,2)) DESC');
    }
    


    $products = $query->get();

    // If the user is authenticated, get their cart data
    if (Auth::id()) {
        $user_id = Auth::user()->id;
        $cartData = Cart::where('user_id', '=', $user_id)->get();
        return view('user.index', compact('products', 'categories', 'cartData'));
    } else {
        return view('user.index', compact('products', 'categories'));
    }
}

    public function Home(Request $request){

        $userType = Auth::user()->usertype;

        /* Admin User */
        if($userType == '1'){

            $total_users = User::where('usertype', 0)->count();
            $products = Product::all();
            $total_product = 0;
            $revenue = 0;
            $sold_products = 0;

            foreach($products as $product){
                $total_product += $product->quantity;
            }

            $total_orders  = Order::where('delivery_status','!=','passive_order')->count();
            $orders = Order::all();
            $delivered_orders = Order::where('delivery_status','=','delivered')->orWhere('delivery_status', '=', 'passive_order')->count();
            $processing_orders = Order::where('delivery_status', '!=', 'passive_order')->count();

            foreach($orders as $order){
                $sold_products += $order->quantity;
                if($order->payment_status == 'paid'){
                    $revenue += $order->price;
                }
            }

            return view('admin.home',compact(
                'total_users',
                'total_product',
                'total_orders',
                'delivered_orders',
                'processing_orders',
                'revenue',
                'sold_products'
            ));

        }else{

            /* Regular User */
            $categories = Category::all();
            $products = Product::all();

            $sort= $request->get('sort', null); // 'null' if no category filter

            // Start building the query to fetch products
            $query = Product::query();

            
            if ($sort === 'asc') {
                // Sorting in ascending order
                $query->orderByRaw('CAST(discount_price AS DECIMAL(10,2)) ASC');
            } elseif ($sort === 'desc') {
                // Sorting in descending order
                $query->orderByRaw('CAST(discount_price AS DECIMAL(10,2)) DESC');
            }
        
         

            $user_id = Auth::user()->id;
            $cartData = Cart::where('user_id', '=', $user_id)->get();
            return view('user.index', compact('products', 'categories','cartData'));
            
        }

    }

    public function UserAccount()
    {
        if (Auth::check()) {
            $userType = Auth::user()->usertype;
            if ($userType == 0) {
                $user = Auth::user();
                $cartData = Cart::where('user_id', '=', $user->id)->get();
                return view('user.my-account',compact('user','cartData'));
            } else {
                return redirect('login');
            }
        } else {
            return redirect('login');
        }
    }

    public function UserLogout(Request $request): RedirectResponse
    {

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Cookie::queue(Cookie::forget('XSRF-TOKEN'));
        Cookie::queue(Cookie::forget('laravel_session'));
        return redirect('/');
    }

    public function ProductDetails($id)
    {
        $product = Product::find($id);

        // check if a user is logged in
        if(Auth::check()){
            $user_id = Auth::user()->id;
            $cartData = Cart::where('user_id', '=', $user_id)->get();
            return view('user.product_details', compact('product', 'cartData'));
        }else{
            return view('user.product_details', compact('product'));
        }
    }

    public function ShopPage()
    {
        $categories = Category::all();
        $products = Product::all();

        // check if a user is logged in
        if(Auth::check()){
            $user_id = Auth::user()->id;
            $cartData = Cart::where('user_id', '=', $user_id)->get();
            return view('user.shop', compact('products', 'categories', 'cartData'));
        }else{
            return view('user.shop', compact('products', 'categories'));
        }
        
    }

    public function ContactPage()
    {
        if(Auth::check()){
            $user_id = Auth::user()->id;
            $cartData = Cart::where('user_id', '=', $user_id)->get();
            return view('user.contact',compact('cartData'));
        }else{
            return view('user.contact');
        }

    }

    public function AddToCart(Request $request, $id)
    {
        if(Auth::check()){

            $user = Auth::user();
            $product = Product::find($id);

            /* check if the requested quantity is more then stock quantity */
            if($request->quantity > $product->quantity){
                Alert::warning('Adding product failed!', 'The requested quantity for this product exceeds the available stock. We have only ' . $product->quantity . ' of this product in out stock.');
                return redirect()->back();
            }else{
                /* check if product already exits in the card
                in this case just the quantity and price should be updated
            */
                $cart = Cart::where('product_id', $product->id)->where('user_id', $user->id)->first();
                if ($cart) {
                    // if the cart record exists, update the quantity and price columns
                    $cart->quantity += $request->quantity;
                    $cart->price += strval(intval($product->discount_price) * intval($request->quantity));
                    $cart->save();
                } else {

                    $cart = new Cart();
                    $cart->user_id = $user->id;
                    $cart->name = $user->name;
                    $cart->email = $user->email;
                    $cart->phone = $user->phone;
                    $cart->address = $user->address;
                    $cart->product_title = $product->title;
                    $cart->product_id = $product->id;
                    $cart->quantity = $request->quantity;
                    $cart->price = strval(intval($product->discount_price) * intval($request->quantity));
                    $cart->image = $product->image;
                    $cart->save();
                }

                // update the quantity in products table
                $product->quantity -= $request->quantity;
                $product->save();
                Alert::success('Product Added Successfully!','We have added product to cart');
                return redirect()->back();
            }

        }else{
            return redirect('login');
        }
    }

    public function CartPage()
    {
        if(Auth::check()){
            $user_id = Auth::user()->id;
            $cartData = Cart::where('user_id', '=', $user_id)->get();
            return view('user.cart', compact('cartData'));
        }else{
            return redirect('login');
        }
    }

    public function RemoveProductFromCart($id)
    {
        if (Auth::check()) {
            $removing_product = Cart::find($id);
            if ($removing_product) {
                $product = Product::find($removing_product->product_id);
                if ($product) {
                    // Update the quantity of the product in the products table
                    $product->quantity += $removing_product->quantity;
                    $product->save();

                    // Remove the product from the cart
                    $removing_product->delete();

                    return redirect()->route('user.cart')->with('success', 'Product removed from cart!');
                } else {
                    return redirect()->back()->with('error', 'Product not found!');
                }
            } else {
                return redirect()->back()->with('error', 'Product not found in cart!');
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function ClearCart()
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
            return redirect()->back()->with('success', 'Cart cleared successfully!');
        } else {
            return redirect('login');
        }
    }

    public function Checkout()
    {
        if(Auth::check()){
            $user_id = Auth::user()->id;
            $cartData = Cart::where('user_id', '=', $user_id)->get();
            return view('user.checkout', compact('cartData'));
        }else{
            return redirect('login');
        }
    }

    public function CashOrder()
    {
        if(Auth::check()){

            $user = Auth::user();
            $user_id = $user->id;
            $cartData = Cart::where('user_id','=',$user_id)->get();

            foreach($cartData as $data){

                $order = new Order();
                $order->user_id = $data->user_id;
                $order->name = $data->name;
                $order->email = $data->email;
                $order->phone = $data->phone;
                $order->address = $data->address;
                $order->product_title = $data->product_title;
                $order->product_id = $data->product_id;
                $order->quantity = $data->quantity;
                $order->price = $data->price;
                $order->image = $data->image;
                $order->tracking_id ='TRK' . Str::limit(uniqid('', true), 15 - strlen('TRK'), '');
                $order->delivery_status = 'pending';
                $order->payment_status = 'cash_on_delivery';
                $order->save();

                
                $cart_id = $data->id;
                $cart = Cart::find($cart_id);
                $cart->delete();
                   
            }
            Alert::success('your order has been received', 'Your order has been received');
            return redirect()->route('user.orders');


        }else{
            redirect('login');
        }
    }

    public function UserOrders()
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $cartData = Cart::where('user_id', '=', $user_id)->get();
            $orderData = Order::where('user_id', '=', $user_id)->where('delivery_status', '<>', 'passive_order')->get();
            $past_orders = Order::where('user_id', '=', $user_id)->where('delivery_status', '=', 'passive_order')->get();
            return view('user.orders', compact('orderData', 'cartData','past_orders'));
        } else {
            return redirect('login');
        }
    }

    public function OrderReceived($id)
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            // get the order you want to update the delivery status for
            $order = Order::where('id', $id)->where('user_id', $user_id)->first();

            if ($order) {
                // update the delivery status
                $order->delivery_status = 'passive_order';
                $order->save();

                // redirect back to the order page with a success message
                return redirect()->back();
            } else {
                // if the order is not found, redirect back with an error message
                return redirect()->back()->with('error', 'Order not found.');
            }

        } else {
            return redirect('login');
        }
    }

    public function CancelOrder($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Get the order that needs to be canceled
            $order = Order::find($id);

            // Create a new cart item for the canceled order
            $cartItem = new Cart();
            $cartItem->user_id = $user->id;
            $cartItem->product_id = $order->product_id;
            $cartItem->quantity = $order->quantity;
            $cartItem->price = $order->price;
            $cartItem->name = $user->name;
            $cartItem->email = $user->email;
            $cartItem->phone = $user->phone;
            $cartItem->address = $user->address;
            $cartItem->product_title = $order->product_title;
            $cartItem->image = $order->image;
            $cartItem->save();

            // Delete the order
            $order->delete();
            Alert::success('Order Cancelled!', 'The Order Has Been Successfully Cancelled');
            return redirect()->back();
        } else {
            return redirect('login');
        }
    }

    public function Stripe($totalPrice)
    {
        if(Auth::check()){
            return view('user.stripe', compact('totalPrice'));
        }else{
            return redirect('login');
        }
    }

    public function StripePost(Request $request, $totalPrice)
    {
        if (Auth::check()) {
            // Assuming payment was successful
            $payment_status = 'paid';  // Set payment status as 'paid'
            $delivery_status = 'processing';  // Set delivery status as 'processing' (or 'packaging', 'shipped', etc.)
    
            // For simplicity, assuming the payment was successfully processed
            // If you need more specific payment result handling, you can add conditional checks
    
            $user = Auth::user();
            $user_id = $user->id;
            $cartData = Cart::where('user_id', '=', $user_id)->get();
    
            foreach ($cartData as $data) {
                $order = new Order();
                $order->user_id = $data->user_id;
                $order->name = $data->name;
                $order->email = $data->email;
                $order->phone = $data->phone;
                $order->address = $data->address;
                $order->product_title = $data->product_title;
                $order->product_id = $data->product_id;
                $order->quantity = $data->quantity;
                $order->price = $data->price;
                $order->image = $data->image;
                $order->tracking_id = 'TRK' . Str::limit(uniqid('', true), 15 - strlen('TRK'), '');
                
                // Use valid ENUM values
                $order->delivery_status = $delivery_status; // 'processing' or other status like 'packaging'
                $order->payment_status = $payment_status; // 'paid'
    
                $order->save();
    
                // Delete cart items after the order is saved
                $cart_id = $data->id;
                $cart = Cart::find($cart_id);
                $cart->delete();
            }
    
            Session::flash('success', 'Payment successful!');
            Alert::success('Payment Successfully Done!', 'Your order has been received');
    
            return redirect()->route('user.orders');
        } else {
            return redirect('login');
        }
    }
    

    public function SearchProduct(Request $request)
    {
        $searchText = $request->search;
        $products  = Product::where('title', 'LIKE', "%$searchText%")->orWhere('ram', 'LIKE', "%$searchText%")->orWhere('category', 'LIKE', "%$searchText%")->get();
        $categories = Category::all();
        // check if a user is logged in
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $cartData = Cart::where('user_id', '=', $user_id)->get();
            return view('user.shop', compact('products', 'categories', 'cartData'));
        } else {
            return view('user.shop', compact('products', 'categories'));
        }
    }

    public function UpdatePassword()
    {
        if(Auth::check()){
            return view('profile.update-profile-information-form');
        }else{
            return redirect('login');
        }
    }

    public function GetTechnologyNews()
    {
        $apiKey = env('NEWS_API_KEY');
        $response = Http::get("https://newsapi.org/v2/top-headlines?category=technology&language=en&pageSize=4&apiKey={$apiKey}");
        $data = $response->json();
        $articles = $data['articles'];
        if(Auth::check()){
            $user_id = Auth::user()->id;
            $cartData = Cart::where('user_id', '=', $user_id)->get();
            return view('user.news', compact('articles','cartData'));
        }else{
            return view('user.news', compact('articles'));
        }

    }


    public function applyCoupon(Request $request)
{
    $request->validate(['coupon_code' => 'required|string']);

    $coupon = Coupon::where('code', $request->coupon_code)->first();

    if (!$coupon || !$coupon->isValid()) {
        return response()->json(['success' => false, 'message' => 'Invalid or expired coupon'], 400);
    }

    if (Auth::check()) {
       
    $user_id = Auth::user()->id;
    $cartData = Cart::where('user_id','=',$user_id)->get();

    $cartTotal = 0 ;
    foreach($cartData as $data) {

        // Calculate the total price for the current item in the cart
        $itemTotal = $data->price ;
        $cartTotal += $itemTotal;  // Add to the total cart value
                   
            }


    if ($cartTotal < $coupon->min_order_amount) {
        return response()->json(['success' => false, 'message' => 'Cart total must be at least $' . $coupon->min_order_amount], 400);
    }

    // Calculate discount
    $discountAmount = ($cartTotal * $coupon->discount_percentage) / 100;
    $newTotal = $cartTotal - $discountAmount;

    // Store the new total in session
    session([
        'coupon_code' => $coupon->code,
        'discount' => $coupon->discount_percentage,
        'discount_amount' => $discountAmount,
        'new_total' => $newTotal
    ]);

     return redirect()->route('user.cart')->with('success', 'Coupon applied successfully!');

  

    } else {
        return redirect('login');
    }
    
    
}


}
