<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::check()) {
            // $user = Auth::user();
            // $user_id = $user->id;
            $app_users = AppUser::all();
            $app_user_count = $app_users->count();
            $order_count = Order::count();
            return view('agents.home', compact('app_users', 'app_user_count', 'order_count'));
        } else {
            return redirect("/login/agent");
        }
    }

    public function order_details()
    {
        if (Auth::check()) {
            $orders = Order::get();
            return view('agents.order', compact('orders'));
        } else {
            return redirect("/login/agent");
        }
    }
}
