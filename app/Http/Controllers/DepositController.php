<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AppUser;
use App\Models\Client;
use App\Models\Deposit;
use App\Models\DepositAgentPercentage;
use App\Models\DepositClientPercentage;
use App\Models\TotalBalance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $app_users = AppUser::all();
        $deposits = Deposit::all();
        return view('deposits.index')->with(['deposits' => $deposits, 'app_users' => $app_users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $app_users = AppUser::all();
        return view('deposits.create')->with(['app_users' => $app_users]);
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
        $client = Auth::guard('client')->user();

        if ($admin) {
            $main = User::find($admin->id);
            $have_agent = $main->have_agent;

            $request->validate([

                'app_user_id' => 'required',

                'amount' => 'required',

                'admin_fee' => 'required',

                'agent_fee' => 'required',

                'client_fee' => 'required',

            ]);

            $app_user_id = $request->input('app_user_id');
            $amount = $request->input('amount');
            $admin_fee = $request->input('admin_fee');
            $agent_fee = $request->input('agent_fee');
            $client_fee = $request->input('client_fee');
            $description = $request->input('description');

            if ($have_agent == 0) {
                // $admin_amount = ($amount * $fee) / 100;
                // $final_amount = $amount - $admin_amount;

                $fee = $admin_fee + $agent_fee + $client_fee;
                $remove_amount = ($amount * $fee) / 100;
                $final_amount = $amount - $remove_amount;

                $deposit = Deposit::create([
                    'app_user_id' => $app_user_id,
                    'amount' => $amount,
                    'fee' => $fee,
                    'final_amount' => $final_amount,
                    'description' => $description
                ]);

                DepositAgentPercentage::create([
                    'deposit_id' => $deposit->id,
                    'total_percent' => $fee,
                    'admin_id' => $main->id,
                    'admin' => $admin_fee,
                    'agent_id' => 0,
                    'agent' => 0,
                ]);

                $total_balance = TotalBalance::where('app_user_id', $app_user_id)->first();
                $total_balance->total_balance += $final_amount;
                $total_balance->save();

                return redirect()->route('deposit.index')->with('success', 'Deposit filled successfully.');
            } elseif ($have_agent == 1) {
                if ($client_fee == 0) {
                    $agent = Agent::find($main->agent_id);
                    $fee = $admin_fee + $agent_fee + $client_fee;
                    $remove_amount = ($amount * $fee) / 100;
                    $final_amount = $amount - $remove_amount;

                    $deposit = Deposit::create([
                        'app_user_id' => $app_user_id,
                        'amount' => $amount,
                        'fee' => $fee,
                        'final_amount' => $final_amount,
                        'description' => $description
                    ]);

                    DepositAgentPercentage::create([
                        'deposit_id' => $deposit->id,
                        'total_percent' => $fee,
                        'admin_id' => $main->id,
                        'admin' => $admin_fee,
                        'agent_id' => $agent->id,
                        'agent' => $agent_fee,
                    ]);

                    $total_balance = TotalBalance::where('app_user_id', $app_user_id)->first();
                    $total_balance->total_balance += $final_amount;
                    $total_balance->save();

                    return redirect()->route('deposit.index')->with('success', 'Deposit filled successfully.');
                } elseif (!$client_fee == 0) {
                    $agent = Agent::find($main->agent_id);
                    $client = Client::find($agent->client_id);
                    $fee = $admin_fee + $agent_fee + $client_fee;
                    $remove_amount = ($amount * $fee) / 100;
                    $final_amount = $amount - $remove_amount;

                    $deposit = Deposit::create([
                        'app_user_id' => $app_user_id,
                        'amount' => $amount,
                        'fee' => $fee,
                        'final_amount' => $final_amount,
                        'description' => $description
                    ]);

                    DepositClientPercentage::create([
                        'deposit_id' => $deposit->id,
                        'total_percent' => $fee,
                        'admin_id' => $main->id,
                        'admin' => $admin_fee,
                        'agent_id' => $agent->id,
                        'agent' => $agent_fee,
                        'parent_client_id' => $client->id,
                        'parent_client' => $client_fee,
                    ]);

                    $total_balance = TotalBalance::where('app_user_id', $app_user_id)->first();
                    $total_balance->total_balance += $final_amount;
                    $total_balance->save();

                    return redirect()->route('deposit.index')->with('success', 'Deposit filled successfully.');
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function show(Deposit $deposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function edit(Deposit $deposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deposit $deposit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deposit $deposit)
    {
        //
    }
}
