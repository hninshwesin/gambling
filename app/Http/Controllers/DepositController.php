<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AppUser;
use App\Models\Client;
use App\Models\Deposit;
use App\Models\DepositAgentPercentage;
use App\Models\DepositClientPercentage;
use App\Models\DepositCommisionAdmin;
use App\Models\DepositCommisionAgent;
use App\Models\DepositCommisionClient;
use App\Models\DepositPercent;
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
        $deposits = Deposit::where('approve_status', '1')->get();
        return view('deposits.index')->with(['deposits' => $deposits]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        return view('deposits.create')->with(['clients' => $clients]);
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

            if ($client->parent_client_id == 0) {

                $percent = DepositPercent::orderBy('id', 'DESC')->first();
                $admin_fee = $percent->admin_percent;
                $agent_fee = $percent->agent_percent;

                $fee = $admin_fee + $agent_fee;
                $admin_amount = ($amount * $admin_fee) / 100;
                $agent_amount = ($amount * $agent_fee) / 100;
                $remove_amount = ($amount * $fee) / 100;
                $final_amount = $amount - $remove_amount;

                $deposit = Deposit::create([
                    'client_id' => $client_id,
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
                    'agent_id' => $client->agent_id,
                    'agent' => $agent_fee,
                ]);

                DepositCommisionAdmin::create([
                    'admin_id' => $main->id,
                    'deposit_id' => $deposit->id,
                    'generate_type' => 0
                ]);

                DepositCommisionAgent::create([
                    'agent_id' => $client->agent_id,
                    'deposit_id' => $deposit->id,
                    'generate_type' => 0
                ]);

                $main->total_balance += $admin_amount;
                $main->save();

                $agent = Agent::find($client->agent_id);
                $agent->total_balance += $agent_amount;
                $agent->save();

                $total_balance = TotalBalance::where('client_id', $client_id)->first();
                $total_balance->total_balance += $final_amount;
                $total_balance->wallet_balance += $amount;
                $total_balance->save();

                return redirect()->route('deposit.index')->with('success', 'Deposit filled successfully.');
            } elseif ($client->parent_client_id != 0) {
                $percent = DepositPercent::orderBy('id', 'DESC')->first();
                $admin_fee = $percent->admin_percent;
                $agent_fee = $percent->agent_percent;
                $client_fee = $percent->client_percent;

                $fee = $admin_fee + $agent_fee + $client_fee;
                $admin_amount = ($amount * $admin_fee) / 100;
                $agent_amount = ($amount * $agent_fee) / 100;
                $client_amount = ($amount * $client_fee) / 100;
                $remove_amount = ($amount * $fee) / 100;
                $final_amount = $amount - $remove_amount;

                $deposit = Deposit::create([
                    'client_id' => $client_id,
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
                    'agent_id' => $client->agent_id,
                    'agent' => $agent_fee
                ]);

                DepositClientPercentage::create([
                    'deposit_id' => $deposit->id,
                    'total_percent' => $fee,
                    'admin_id' => $main->id,
                    'admin' => $admin_fee,
                    'agent_id' => $client->agent_id,
                    'agent' => $agent_fee,
                    'parent_client_id' => $client->parent_client_id,
                    'parent_client' => $client_fee
                ]);

                DepositCommisionAdmin::create([
                    'admin_id' => $main->id,
                    'deposit_id' => $deposit->id,
                    'generate_type' => 1
                ]);

                DepositCommisionAgent::create([
                    'agent_id' => $client->agent_id,
                    'deposit_id' => $deposit->id,
                    'generate_type' => 1
                ]);

                DepositCommisionClient::create([
                    'client_id' => $client->parent_client_id,
                    'deposit_id' => $deposit->id,
                    'generate_type' => 1
                ]);

                $main->total_balance += $admin_amount;
                $main->save();

                $agent = Agent::find($client->agent_id);
                $agent->total_balance += $agent_amount;
                $agent->save();

                $parent_client = TotalBalance::where('client_id', $client->parent_client_id)->first();
                $parent_client->total_balance += $client_amount;
                $parent_client->save();

                $total_balance = TotalBalance::where('client_id', $client_id)->first();
                $total_balance->total_balance += $final_amount;
                $total_balance->wallet_balance += $amount;
                $total_balance->save();

                return redirect()->route('deposit.index')->with('success', 'Deposit filled successfully.');
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

    public function deposit_request()
    {
        $deposits = Deposit::where('approve_status', '0')->get();
        return view('deposits.approve')->with(['deposits' => $deposits]);
    }

    public function deposit_approve(Request $request)
    {
        $deposit_id = $request->input('deposit_id');
        $deposit = Deposit::find($deposit_id);

        $admin = Auth::guard('web')->user();
        $main = User::find($admin->id);

        if ($main) {
            if ($deposit) {

                $client = Client::find($deposit->client_id);

                if ($client) {
                    $deposit->approve_status = 1;
                    $deposit->save();

                    $amount = $deposit->amount;

                    if ($client->parent_client_id == 0) {
                        // dd('no parent client, true');
                        $percent = DepositPercent::orderBy('id', 'DESC')->first();
                        $admin_fee = $percent->admin_percent;
                        $agent_fee = $percent->agent_percent;

                        $fee = $admin_fee + $agent_fee;
                        $admin_amount = ($amount * $admin_fee) / 100;
                        $agent_amount = ($amount * $agent_fee) / 100;
                        $remove_amount = ($amount * $fee) / 100;
                        $final_amount = $amount - $remove_amount;

                        DepositAgentPercentage::create([
                            'deposit_id' => $deposit->id,
                            'total_percent' => $fee,
                            'admin_id' => $main->id,
                            'admin' => $admin_fee,
                            'agent_id' => $client->agent_id,
                            'agent' => $agent_fee,
                        ]);

                        DepositCommisionAdmin::create([
                            'admin_id' => $main->id,
                            'deposit_id' => $deposit->id,
                            'generate_type' => 0
                        ]);

                        DepositCommisionAgent::create([
                            'agent_id' => $client->agent_id,
                            'deposit_id' => $deposit->id,
                            'generate_type' => 0
                        ]);

                        $main->total_balance += $admin_amount;
                        $main->save();

                        $agent = Agent::find($client->agent_id);
                        $agent->total_balance += $agent_amount;
                        $agent->save();

                        $total_balance = TotalBalance::where('client_id', $client->id)->first();
                        $total_balance->total_balance += $final_amount;
                        $total_balance->wallet_balance += $amount;
                        $total_balance->save();
                    } elseif ($client->parent_client_id != 0) {
                        // dd('parent client, true');
                        $percent = DepositPercent::orderBy('id', 'DESC')->first();
                        $admin_fee = $percent->admin_percent;
                        $agent_fee = $percent->agent_percent;
                        $client_fee = $percent->client_percent;

                        $fee = $admin_fee + $agent_fee + $client_fee;
                        $admin_amount = ($amount * $admin_fee) / 100;
                        $agent_amount = ($amount * $agent_fee) / 100;
                        $client_amount = ($amount * $client_fee) / 100;
                        $remove_amount = ($amount * $fee) / 100;
                        $final_amount = $amount - $remove_amount;

                        DepositAgentPercentage::create([
                            'deposit_id' => $deposit->id,
                            'total_percent' => $fee,
                            'admin_id' => $main->id,
                            'admin' => $admin_fee,
                            'agent_id' => $client->agent_id,
                            'agent' => $agent_fee
                        ]);

                        DepositClientPercentage::create([
                            'deposit_id' => $deposit->id,
                            'total_percent' => $fee,
                            'admin_id' => $main->id,
                            'admin' => $admin_fee,
                            'agent_id' => $client->agent_id,
                            'agent' => $agent_fee,
                            'parent_client_id' => $client->parent_client_id,
                            'parent_client' => $client_fee
                        ]);

                        DepositCommisionAdmin::create([
                            'admin_id' => $main->id,
                            'deposit_id' => $deposit->id,
                            'generate_type' => 1
                        ]);

                        DepositCommisionAgent::create([
                            'agent_id' => $client->agent_id,
                            'deposit_id' => $deposit->id,
                            'generate_type' => 1
                        ]);

                        DepositCommisionClient::create([
                            'client_id' => $client->parent_client_id,
                            'deposit_id' => $deposit->id,
                            'generate_type' => 1
                        ]);

                        $main->total_balance += $admin_amount;
                        $main->save();

                        $agent = Agent::find($client->agent_id);
                        $agent->total_balance += $agent_amount;
                        $agent->save();

                        $parent_client = TotalBalance::where('client_id', $client->parent_client_id)->first();
                        $parent_client->total_balance += $client_amount;
                        $parent_client->save();

                        $total_balance = TotalBalance::where('client_id', $client->id)->first();
                        $total_balance->total_balance += $final_amount;
                        $total_balance->wallet_balance += $amount;
                        $total_balance->save();
                    }
                    return redirect()->back()->with('success', 'Deposit Request has been approved');
                } else {
                    return redirect()->back()->with('error', 'Client does not exist');
                }
            } else {
                return redirect()->back()->with('error', 'Deposit does not exist');
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong.Please Sign in again!');
        }
    }
}
