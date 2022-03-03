<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
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
            // $user = Auth::user();
            $user = Auth::guard('agent')->user();
            $agent = Agent::find($user->id);
            $total_balance = $user->total_balance;
            $clients = Client::where('agent_id', $agent->id)->get();
            $client_count = $clients->count();
            $orders = Order::where('agent_id', $agent->id)->get();
            $order_count = $orders->count();
            return view('agents.home', compact('clients', 'total_balance', 'client_count', 'order_count'));
        } else {
            return redirect("/login/agent");
        }
    }

    public function order_details()
    {
        if (Auth::check()) {
            $user = Auth::guard('agent')->user();
            $agent = Agent::find($user->id);
            $orders = Order::where('agent_id', $agent->id)->get();
            return view('agents.order', compact('orders'));
        } else {
            return redirect("/login/agent");
        }
    }
}
