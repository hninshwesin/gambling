<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentWithdraw;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agents = Agent::all();
        return view('agent_detail')->with(['agents' => $agents]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $agent = Agent::find($id);
        if ($agent) {
            // dd('hello delete');
            $agent->delete();

            return redirect()->back()->with('success', 'Agent deleted successfully');
        }
    }

    public function agent_withdraw_request()
    {
        $agent_withdraws = AgentWithdraw::where('approve_status', '0')->get();
        return view('agent_withdraw_request')->with(['agent_withdraws' => $agent_withdraws]);
    }

    public function agent_withdraw_approve(Request $request)
    {
        $withdraw_id = $request->input('withdraw_id');
        $withdraw = AgentWithdraw::find($withdraw_id);

        $admin = Auth::guard('web')->user();
        $main = User::find($admin->id);

        if ($main) {
            if ($withdraw) {
                $remove_amount = $withdraw->amount;
                $withdraw->agent->total_balance -= $remove_amount;
                $withdraw->approve_status = 1;
                $withdraw->save();
                $withdraw->agent->save();

                return redirect()->route('agent_detail.index')->with('success', 'Agent Withdraw Request has been approved');
            } else {
                return redirect()->back()->with('failed', 'Withdraw does not exist');
            }
        } else {
            return redirect()->back()->with('failed', 'Something went wrong.Please Sign in again!');
        }
    }

    public function agent_withdraw_history()
    {
        $agent_withdraws = AgentWithdraw::all();
        return view('agent_withdraw_history')->with(['agent_withdraws' => $agent_withdraws]);
    }
}
