<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
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
        return view('agents.withdraws.index')->with(['withdraws' => $withdraws, 'app_users' => $app_users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $app_users = AppUser::all();
        return view('agents.withdraws.create')->with(['app_users' => $app_users]);
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
            $main = User::where('agent_id', $user->id)->first();
            $agent = Agent::find($user->id);

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

            return redirect()->route('agents.withdraw.index')->with('success', 'Withdraw amount filled successfully.');
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
