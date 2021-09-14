<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AppUser;
use App\Models\Client;
use App\Models\Deposit;
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
        return view('clients.deposits.index')->with(['deposits' => $deposits, 'app_users' => $app_users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $app_users = AppUser::all();
        return view('clients.deposits.create')->with(['app_users' => $app_users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::guard('client')->user();

        if ($user) {
            $client = Client::find($user->id);

            $request->validate([

                'app_user_id' => 'required',

                'amount' => 'required',

                'admin_fee' => 'required',

                'agent_fee' => 'required',

                'client_fee' => 'required'

            ]);

            $app_user_id = $request->input('app_user_id');
            $amount = $request->input('amount');
            $admin_fee = $request->input('admin_fee');
            $agent_fee = $request->input('agent_fee');
            $client_fee = $request->input('client_fee');
            $description = $request->input('description');

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

            $agent = Agent::find($client->agent_id);

            DepositClientPercentage::create([
                'deposit_id' => $deposit->id,
                'total_percent' => $fee,
                'admin_id' => $agent->user_id,
                'admin' => $admin_fee,
                'agent_id' => $client->agent_id,
                'agent' => $agent_fee,
                'parent_client_id' => $client->parent_client_id,
                'parent_client' => $client_fee
            ]);

            $total_balance = TotalBalance::where('app_user_id', $app_user_id)->first();
            $total_balance->total_balance += $final_amount;
            $total_balance->save();

            return redirect()->route('deposit.index')->with('success', 'Deposit filled successfully.');
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
