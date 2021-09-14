<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AppUser;
use App\Models\Client;
use App\Models\TotalBalance;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\WithdrawAgentPercentage;
use App\Models\WithdrawCommisionAdmin;
use App\Models\WithdrawCommisionAgent;
use App\Models\WithdrawPercent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $withdraws = Withdraw::all();
        return view('agents.withdraws.index')->with(['withdraws' => $withdraws]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        return view('agents.withdraws.create')->with(['clients' => $clients]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::guard('agent')->user();

        if ($user) {
            $agent = Agent::find($user->id);

            $request->validate([

                'client_id' => 'required',

                'amount' => 'required'

            ]);

            $client_id = $request->input('client_id');
            $amount = $request->input('amount');
            $description = $request->input('description');

            $client = Client::find($client_id);

            $percent = WithdrawPercent::first();
            $admin_fee = $percent->admin_percent;
            $agent_fee = $percent->agent_percent;

            $fee = $admin_fee + $agent_fee;
            $admin_amount = ($amount * $admin_fee) / 100;
            $agent_amount = ($amount * $agent_fee) / 100;
            $remove_amount = ($amount * $fee) / 100;
            $final_amount = $amount - $remove_amount;

            $withdraw = Withdraw::create([
                'client_id' => $client_id,
                'amount' => $amount,
                'fee' => $fee,
                'final_amount' => $final_amount,
                'description' => $description
            ]);

            WithdrawAgentPercentage::create([
                'withdraw_id' => $withdraw->id,
                'total_percent' => $fee,
                'admin_id' => $agent->user_id,
                'admin' => $admin_fee,
                'agent_id' => $agent->id,
                'agent' => $agent_fee,
            ]);

            WithdrawCommisionAdmin::create([
                'admin_id' => $agent->user_id,
                'withdraw_id' => $withdraw->id
            ]);

            WithdrawCommisionAgent::create([
                'agent_id' => $agent->id,
                'withdraw_id' => $withdraw->id
            ]);

            $agent->total_balance += $agent_amount;
            $agent->save();

            $admin = User::find($agent->user_id);
            $admin->total_balance += $admin_amount;
            $admin->save();

            $total_balance = TotalBalance::where('client_id', $client_id)->first();
            $total_balance->total_balance -= $amount;
            $total_balance->save();

            return redirect()->route('withdraw.index')->with('success', 'Withdraw filled successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
