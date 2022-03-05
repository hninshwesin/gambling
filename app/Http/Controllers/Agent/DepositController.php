<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
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
        $user = Auth::guard('agent')->user();
        $agent = Agent::find($user->id);

        $deposits = Deposit::where('agent_id', $agent->id)->where('approve_status', 1)->get();
        return view('agents.deposits.index')->with(['deposits' => $deposits]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::guard('agent')->user();
        $agent = Agent::find($user->id);

        $clients = Client::where('agent_id', $agent->id)->get();
        return view('agents.deposits.create')->with(['clients' => $clients]);
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

            if ($agent->total_balance > $amount) {
                $client = Client::find($client_id);

                if ($client) {
                    if ($client->agent_id == $agent->id) {
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
                                'description' => $description,
                                'approve_status' => 1,
                                'agent_id' => $agent->id
                            ]);

                            DepositAgentPercentage::create([
                                'deposit_id' => $deposit->id,
                                'total_percent' => $fee,
                                'admin_id' => $agent->user_id,
                                'admin' => $admin_fee,
                                'agent_id' => $agent->id,
                                'agent' => $agent_fee
                            ]);

                            DepositCommisionAdmin::create([
                                'admin_id' => $agent->user_id,
                                'deposit_id' => $deposit->id,
                                'generate_type' => 0
                            ]);

                            DepositCommisionAgent::create([
                                'agent_id' => $agent->id,
                                'deposit_id' => $deposit->id,
                                'generate_type' => 0
                            ]);

                            $agent->total_balance += $agent_amount;
                            $agent->save();

                            $admin = User::find($agent->user_id);
                            $admin->total_balance += $admin_amount;
                            $admin->save();

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

                            $fee = $admin_fee + $agent_fee;
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
                                'description' => $description,
                                'approve_status' => 1,
                                'agent_id' => $agent->id
                            ]);

                            DepositAgentPercentage::create([
                                'deposit_id' => $deposit->id,
                                'total_percent' => $fee,
                                'admin_id' => $agent->user_id,
                                'admin' => $admin_fee,
                                'agent_id' => $agent->id,
                                'agent' => $agent_fee
                            ]);

                            DepositClientPercentage::create([
                                'deposit_id' => $deposit->id,
                                'total_percent' => $fee,
                                'admin_id' => $agent->user_id,
                                'admin' => $admin_fee,
                                'agent_id' => $agent->id,
                                'agent' => $agent_fee,
                                'parent_client_id' => $client->parent_client_id,
                                'parent_client' => $client_fee
                            ]);

                            DepositCommisionAdmin::create([
                                'admin_id' => $agent->user_id,
                                'deposit_id' => $deposit->id,
                                'generate_type' => 1
                            ]);

                            DepositCommisionAgent::create([
                                'agent_id' => $agent->id,
                                'deposit_id' => $deposit->id,
                                'generate_type' => 1
                            ]);

                            DepositCommisionClient::create([
                                'client_id' => $client->parent_client_id,
                                'deposit_id' => $deposit->id,
                                'generate_type' => 1
                            ]);

                            $agent->total_balance += $agent_amount;
                            $agent->save();

                            $admin = User::find($agent->user_id);
                            $admin->total_balance += $admin_amount;
                            $admin->save();

                            $parent_client = TotalBalance::where('client_id', $client->parent_client_id)->first();
                            $parent_client->total_balance += $client_amount;
                            $parent_client->wallet_balance += $client_amount;
                            $parent_client->save();

                            $total_balance = TotalBalance::where('client_id', $client_id)->first();
                            $total_balance->total_balance += $final_amount;
                            $total_balance->wallet_balance += $amount;
                            $total_balance->save();

                            return redirect()->route('deposit.index')->with('success', 'Deposit filled successfully.');
                        }
                    } else {
                        return redirect()->back()->with('error', 'This is not your client!');
                    }
                } else {
                    return redirect()->back()->with('error', 'Client do not exist!');
                }
            } else {
                return redirect()->back()->with('error', 'Your amount is not sufficient!');
            }
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

    public function deposit_request_from_client()
    {
        /**Approve client deposit */
        $user = Auth::guard('agent')->user();
        $agent = Agent::find($user->id);

        $deposits = Deposit::where('agent_id', $agent->id)->where('approve_status', '0')->get();
        return view('agents.deposits.approve')->with(['deposits' => $deposits]);
    }

    public function deposit_approve_from_client(Request $request)
    {
        $deposit_id = $request->input('deposit_id');
        $deposit = Deposit::find($deposit_id);

        $user = Auth::guard('agent')->user();
        $agent = Agent::find($user->id);


        $main = User::find($agent->user_id);

        if ($agent) {

            if ($deposit) {
                if ($agent->total_balance > $deposit->amount) {
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

                            // $agent = Agent::find($client->agent_id);
                            $agent->total_balance += $agent_amount;
                            $agent->save();

                            $total_balance = TotalBalance::where('client_id', $client->id)->first();
                            $total_balance->total_balance += $final_amount;
                            $total_balance->wallet_balance += $amount;
                            $total_balance->save();

                            // return redirect()->back()->with('success', 'Deposit Request has been approved');
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

                            // $agent = Agent::find($client->agent_id);
                            $agent->total_balance += $agent_amount;
                            $agent->save();

                            $parent_client = TotalBalance::where('client_id', $client->parent_client_id)->first();
                            $parent_client->total_balance += $client_amount;
                            $parent_client->wallet_balance += $client_amount;
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
                    return redirect()->back()->with('error', 'Your amount is not sufficient!');
                }
            } else {
                return redirect()->back()->with('error', 'Deposit does not exist');
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong.Please Sign in again!');
        }
    }
}
