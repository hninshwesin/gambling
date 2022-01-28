<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use App\Models\Client;
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
            $user = Auth::user();
            $total_balance = $user->total_balance;
            $clients = Client::all();
            $client_count = $clients->count();
            $order_count = Order::count();
            return view('home', compact('clients', 'total_balance', 'client_count', 'order_count'));
        } else {
            return redirect("/login");
        }
    }

    public function order_details()
    {
        if (Auth::check()) {
            $orders = Order::OrderBy('id', 'desc')->get();

            return view('order', compact('orders'));
        } else {
            return redirect("/login");
        }
    }
}
