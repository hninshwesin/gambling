<?php

namespace App\Http\Controllers;

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
        $withdraws = Withdraw::where('approve_status', '1')->get();
        return view('withdraws.index')->with(['withdraws' => $withdraws]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /**Fill withdraw from admin directly */

        // $clients = Client::all();
        // return view('withdraws.create')->with(['clients' => $clients]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $admin = Auth::guard('web')->user();

        if ($admin) {
            $main = User::find($admin->id);

            $request->validate([

                'client_id' => 'required',

                'amount' => 'required'

            ]);

            $client_id = $request->input('client_id');
            $amount = $request->input('amount');
            $description = $request->input('description');

            $client = Client::find($client_id);

            $agent = Agent::find($client->agent_id);

            $percent = WithdrawPercent::orderBy('id', 'DESC')->first();
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
                'description' => $description,
                'agent_id' => $agent->id
            ]);

            WithdrawAgentPercentage::create([
                'withdraw_id' => $withdraw->id,
                'total_percent' => $fee,
                'admin_id' => $main->id,
                'admin' => $admin_fee,
                'agent_id' => $client->agent_id,
                'agent' => $agent_fee,
            ]);

            WithdrawCommisionAdmin::create([
                'admin_id' => $main->id,
                'withdraw_id' => $withdraw->id
            ]);

            WithdrawCommisionAgent::create([
                'agent_id' => $client->agent_id,
                'withdraw_id' => $withdraw->id
            ]);

            $main->total_balance += $admin_amount;
            $main->save();


            $agent->total_balance += $agent_amount;
            $agent->save();

            $total_balance = TotalBalance::where('client_id', $client_id)->first();
            $total_balance->total_balance -= $amount;
            $total_balance->wallet_balance -= $amount;
            $total_balance->save();

            return redirect()->route('withdraw.index')->with('success', 'Withdraw filled successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function show(Withdraw $withdraw)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function edit(Withdraw $withdraw)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Withdraw $withdraw)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function destroy(Withdraw $withdraw)
    {
        //
    }

    public function withdraw_request()
    {
        /**Approve client withdraw */

        // $withdraws = Withdraw::where('approve_status', '0')->get();
        // return view('withdraws.approve')->with(['withdraws' => $withdraws]);
    }

    public function withdraw_approve(Request $request)
    {
        $withdraw_id = $request->input('withdraw_id');
        $withdraw = Withdraw::find($withdraw_id);

        $admin = Auth::guard('web')->user();
        $main = User::find($admin->id);

        if ($main) {
            if ($withdraw) {

                $client = Client::find($withdraw->client_id);

                if ($client) {
                    // dd('hello');
                    $withdraw->approve_status = 1;
                    $withdraw->save();

                    $amount = $withdraw->amount;

                    $percent = WithdrawPercent::orderBy('id', 'DESC')->first();
                    $admin_fee = $percent->admin_percent;
                    $agent_fee = $percent->agent_percent;

                    $fee = $admin_fee + $agent_fee;
                    $admin_amount = ($amount * $admin_fee) / 100;
                    $agent_amount = ($amount * $agent_fee) / 100;
                    $remove_amount = ($amount * $fee) / 100;
                    $final_amount = $amount - $remove_amount;

                    WithdrawAgentPercentage::create([
                        'withdraw_id' => $withdraw->id,
                        'total_percent' => $fee,
                        'admin_id' => $main->id,
                        'admin' => $admin_fee,
                        'agent_id' => $client->agent_id,
                        'agent' => $agent_fee,
                    ]);

                    WithdrawCommisionAdmin::create([
                        'admin_id' => $main->id,
                        'withdraw_id' => $withdraw->id
                    ]);

                    WithdrawCommisionAgent::create([
                        'agent_id' => $client->agent_id,
                        'withdraw_id' => $withdraw->id
                    ]);

                    $main->total_balance += $admin_amount;
                    $main->save();

                    $agent = Agent::find($client->agent_id);
                    $agent->total_balance += $agent_amount;
                    $agent->save();

                    $total_balance = TotalBalance::where('client_id', $client->id)->first();
                    $total_balance->total_balance -= $amount;
                    $total_balance->wallet_balance -= $amount;
                    $total_balance->save();


                    return redirect()->back()->with('success', 'Withdraw Request has been approved');
                } else {
                    return redirect()->back()->with('error', 'Client does not exist');
                }
            } else {
                return redirect()->back()->with('error', 'Withdraw does not exist');
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong.Please Sign in again!');
        }
    }
}
