<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\Client;
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
            $user = Auth::user();
            $total_balance = $user->total_balance;
            $clients = Client::all();
            $client_count = $clients->count();
            $order_count = Order::count();
            return view('agents.home', compact('clients', 'total_balance', 'client_count', 'order_count'));
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
