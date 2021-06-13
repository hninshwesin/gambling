<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            // $user = Auth::user();
            // $user_id = $user->id;
            $app_users = AppUser::all();
            $app_user_count = $app_users->count();
            $order_count = Order::count();
            return view('home', compact('app_users', 'app_user_count', 'order_count'));
        } else {
            return redirect("/login");
        }
    }

    public function order_details()
    {
        if (Auth::check()) {
            $app_users = AppUser::all();
            $orders = Order::get();

            return view('order', compact('app_users', 'orders'));
        } else {
            return redirect("/login");
        }
    }
}
