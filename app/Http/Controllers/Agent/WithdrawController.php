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
        $user = Auth::guard('agent')->user();
        $agent = Agent::find($user->id);

        $withdraws = Withdraw::where('agent_id', $agent->id)->where('approve_status', 1)->get();
        return view('agents.withdraws.index')->with(['withdraws' => $withdraws]);
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
        $agent = Agent::find($user->id);

        if ($agent) {
            $request->validate([

                'client_id' => 'required',

                'amount' => 'required'

            ]);

            $client_id = $request->input('client_id');
            $amount = $request->input('amount');
            $description = $request->input('description');

            $client = Client::find($client_id);

            if ($client) {
                if ($client->total_balances->total_balance >= $amount) {
                    $percent = WithdrawPercent::orderBy('id', 'DESC')->first();
                    $admin_fee = $percent->admin_percent;
                    $agent_fee = $percent->agent_percent;

                    $fee = $admin_fee + $agent_fee;
                    // $admin_amount = ($amount * $admin_fee) / 100;
                    // $agent_amount = ($amount * $agent_fee) / 100;
                    $remove_amount = ($amount * $fee) / 100;
                    $final_amount = $amount - $remove_amount;

                    $withdraw = Withdraw::create([
                        'client_id' => $client_id,
                        'amount' => $amount,
                        'fee' => $fee,
                        'final_amount' => $final_amount,
                        'description' => $description,
                        'approve_status' => 0,
                        'agent_id' => $agent->id
                    ]);

                    return redirect()->route('withdraw.index')->with('success', 'Withdraw requested successfully.');
                } else {
                    return redirect()->back()->with('error', 'Withdraw amount is not sufficient!');
                }
            } else {
                return redirect()->back()->with('error', 'Client does not exist');
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong.Please Sign in again!');
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

    public function direct_withdraw()
    {
        $user = Auth::guard('agent')->user();
        $agent = Agent::find($user->id);

        $clients = Client::where('agent_id', $agent->id)->get();
        return view('agents.withdraws.direct_withdraw')->with(['clients' => $clients]);
    }

    public function make_direct_withdraw(Request $request)
    {
        $user = Auth::guard('agent')->user();
        $agent = Agent::find($user->id);

        if ($agent) {

            $request->validate([

                'client_id' => 'required',

                'amount' => 'required'

            ]);

            $client_id = $request->input('client_id');
            $amount = $request->input('amount');
            $description = $request->input('description');

            $client = Client::find($client_id);

            if ($client) {
                if ($client->total_balances->total_balance >= $amount) {
                    if ($client->agent_id == $agent->id) {
                        // if ($agent->total_balance > $amount) {
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
                            'approve_status' => 1,
                            'agent_id' => $agent->id
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

                        $agent->total_balance += $amount + $agent_amount;
                        $agent->save();

                        // $agent_of_client = Agent::find($client->agent_id);
                        // $agent_of_client->total_balance += $agent_amount;
                        // $agent->save();

                        $admin = User::find($agent->user_id);
                        $admin->total_balance += $admin_amount;
                        $admin->save();

                        $total_balance = TotalBalance::where('client_id', $client->id)->first();
                        $total_balance->total_balance -= $amount;
                        $total_balance->wallet_balance -= $amount;
                        $total_balance->save();

                        return redirect()->route('withdraw.index')->with('success', 'Withdraw successfully.');
                        // } else {
                        //     return redirect()->back()->with('error', 'Your amount is not sufficient');
                        // }
                    } else {
                        return redirect()->back()->with('error', 'You don\'t have access');
                        // if ($agent->total_balance > $amount) {
                        // $percent = WithdrawPercent::orderBy('id', 'DESC')->first();
                        // $admin_fee = $percent->admin_percent;
                        // $agent_fee = $percent->agent_percent;

                        // $fee = $admin_fee + $agent_fee;
                        // $admin_amount = ($amount * $admin_fee) / 100;
                        // $agent_amount = ($amount * $agent_fee) / 100;
                        // $remove_amount = ($amount * $fee) / 100;
                        // $final_amount = $amount - $remove_amount;

                        // $withdraw = Withdraw::create([
                        //     'client_id' => $client_id,
                        //     'amount' => $amount,
                        //     'fee' => $fee,
                        //     'final_amount' => $final_amount,
                        //     'description' => $description,
                        //     'approve_status' => 1,
                        //     'agent_id' => $agent->id
                        // ]);

                        // WithdrawAgentPercentage::create([
                        //     'withdraw_id' => $withdraw->id,
                        //     'total_percent' => $fee,
                        //     'admin_id' => $agent->user_id,
                        //     'admin' => $admin_fee,
                        //     'agent_id' => $agent->id,
                        //     'agent' => $agent_fee,
                        // ]);

                        // WithdrawCommisionAdmin::create([
                        //     'admin_id' => $agent->user_id,
                        //     'withdraw_id' => $withdraw->id
                        // ]);

                        // WithdrawCommisionAgent::create([
                        //     'agent_id' => $agent->id,
                        //     'withdraw_id' => $withdraw->id
                        // ]);

                        // $agent->total_balance += $amount;
                        // $agent->save();

                        // $agent_of_client = Agent::find($client->agent_id);
                        // $agent_of_client->total_balance += $agent_amount;
                        // $agent_of_client->save();

                        // $admin = User::find($agent->user_id);
                        // $admin->total_balance += $admin_amount;
                        // $admin->save();

                        // $total_balance = TotalBalance::where('client_id', $client->id)->first();
                        // $total_balance->total_balance -= $amount;
                        // $total_balance->wallet_balance -= $amount;
                        // $total_balance->save();

                        // return redirect()->route('withdraw.index')->with('success', 'Withdraw successfully.');
                        // // } else {
                        // //     return redirect()->back()->with('error', 'Your amount is not sufficient');
                        // // }
                    }
                } else {
                    return redirect()->back()->with('error', 'Withdraw amount is not sufficient!');
                }
            } else {
                return redirect()->back()->with('error', 'Client does not exist!');
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong.Please Sign in again!');
        }
    }

    public function withdraw_request_from_client()
    {
        /**Approve client withdraw */
        $user = Auth::guard('agent')->user();
        $agent = Agent::find($user->id);

        $withdraws = Withdraw::where('agent_id', $agent->id)->where('approve_status', '0')->get();
        return view('agents.withdraws.approve')->with(['withdraws' => $withdraws]);
    }

    public function withdraw_approve_from_client(Request $request)
    {
        $withdraw_id = $request->input('withdraw_id');
        $withdraw = Withdraw::find($withdraw_id);

        $user = Auth::guard('agent')->user();
        $agent = Agent::find($user->id);

        $main = User::find($agent->user_id);

        if ($agent) {
            if ($withdraw) {

                $client = Client::find($withdraw->client_id);

                if ($client) {
                    // dd('hello');
                    if ($client->total_balances->total_balance >= $withdraw->amount) {
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
                        // $final_amount = $amount - $remove_amount;

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

                        // $agent = Agent::find($client->agent_id);
                        $agent->total_balance += $agent_amount;
                        $agent->save();

                        $total_balance = TotalBalance::where('client_id', $client->id)->first();
                        $total_balance->total_balance -= $amount;
                        $total_balance->wallet_balance -= $amount;
                        $total_balance->save();


                        return redirect()->back()->with('success', 'Withdraw Request has been approved');
                    } else {
                        return redirect()->back()->with('error', 'Client request amount is not sufficient!');
                    }
                } else {
                    return redirect()->back()->with('error', 'Client does not exist!');
                }
            } else {
                return redirect()->back()->with('error', 'Withdraw does not exist!');
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong.Please Sign in again!');
        }
    }
}
