<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AppUser;
use App\Models\Client;
use App\Models\Deposit;
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

            $get_system_balance = Deposit::where('approve_status', 1)->get();
            $system_balance = $get_system_balance->sum('amount');
            // dd($system_balance);

            $get_daily_system_balance = Deposit::whereDate('updated_at', now())->get();
            $daily_system_balance = $get_daily_system_balance->sum('amount');
            // dd($daily_system_balance);
            $agents = Agent::count();

            return view('home', compact('clients', 'total_balance', 'client_count', 'order_count', 'system_balance', 'daily_system_balance', 'agents'));
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
