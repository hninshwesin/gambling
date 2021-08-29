<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AppUser;
use App\Models\TotalBalance;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\WithdrawAgentPercentage;
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
        $app_users = AppUser::all();
        $withdraws = Withdraw::all();
        return view('withdraws.index')->with(['withdraws' => $withdraws, 'app_users' => $app_users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $app_users = AppUser::all();
        return view('withdraws.create')->with(['app_users' => $app_users]);
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
            $have_agent = $main->have_agent;

            $request->validate([

                'app_user_id' => 'required',

                'amount' => 'required',

                'admin_fee' => 'required',

                'agent_fee' => 'required',

            ]);

            $app_user_id = $request->input('app_user_id');
            $amount = $request->input('amount');
            $admin_fee = $request->input('admin_fee');
            $agent_fee = $request->input('agent_fee');
            $description = $request->input('description');

            if ($have_agent == 0) {
                $fee = $admin_fee + $agent_fee;
                $remove_amount = ($amount * $fee) / 100;
                $final_amount = $amount - $remove_amount;

                $withdraw = Withdraw::create([
                    'app_user_id' => $app_user_id,
                    'amount' => $amount,
                    'fee' => $fee,
                    'final_amount' => $final_amount,
                    'description' => $description
                ]);

                WithdrawAgentPercentage::create([
                    'withdraw_id' => $withdraw->id,
                    'total_percent' => $fee,
                    'admin_id' => $main->id,
                    'admin' => $admin_fee,
                    'agent_id' => 0,
                    'agent' => 0,
                ]);

                $total_balance = TotalBalance::where('app_user_id', $app_user_id)->first();
                $total_balance->total_balance -= $final_amount;
                $total_balance->save();

                return redirect()->route('withdraw.index')->with('success', 'Withdraw amount filled successfully.');
            } elseif ($have_agent == 1) {
                $agent = Agent::find($main->agent_id);
                $fee = $admin_fee + $agent_fee;
                $remove_amount = ($amount * $fee) / 100;
                $final_amount = $amount - $remove_amount;

                $withdraw = Withdraw::create([
                    'app_user_id' => $app_user_id,
                    'amount' => $amount,
                    'fee' => $fee,
                    'final_amount' => $final_amount,
                    'description' => $description
                ]);

                WithdrawAgentPercentage::create([
                    'withdraw_id' => $withdraw->id,
                    'total_percent' => $fee,
                    'admin_id' => $main->id,
                    'admin' => $admin_fee,
                    'agent_id' => $agent->id,
                    'agent' => $agent_fee,
                ]);

                $total_balance = TotalBalance::where('app_user_id', $app_user_id)->first();
                $total_balance->total_balance -= $final_amount;
                $total_balance->save();

                return redirect()->route('withdraw.index')->with('success', 'Withdraw amount filled successfully.');
            }
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
}
