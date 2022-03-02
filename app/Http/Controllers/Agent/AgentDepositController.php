<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentDepositController extends Controller
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

        if ($agent) {
            $agent_deposits = AgentDeposit::where('agent_id', $agent->id)->get();
            return view('agents/agent_deposits.index')->with(['agent_deposits' => $agent_deposits]);
        }
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

        return view('agents/agent_deposits.create')->with(['agent' => $agent]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer'
        ]);
        $deposit_amount = $request->input('amount');
        $description = $request->input('description');

        $user = Auth::guard('agent')->user();
        $agent = Agent::find($user->id);

        if ($agent) {

            $deposit_record = AgentDeposit::where('agent_id', $agent->id)->where('approve_status', 0)->first();

            if (!$deposit_record) {
                AgentDeposit::create([
                    'agent_id' => $agent->id,
                    'amount' => $deposit_amount,
                    'description' => $description,
                    'approve_status' => 0
                ]);

                return redirect()->route('agent_deposit.index')->with('success', 'Deposit requested successfully.');
            } else {
                return redirect()->back()->with('error', 'You have already requested money to add Deposit, Please contact with Admin first!');
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
}
