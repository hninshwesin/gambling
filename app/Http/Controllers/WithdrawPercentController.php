<?php

namespace App\Http\Controllers;

use App\Models\WithdrawPercent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawPercentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $withdrawPercents = WithdrawPercent::all();
        return view('withdrawPercents.index')->with(['withdrawPercents' => $withdrawPercents]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('withdrawPercents.create');
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
            $request->validate([

                'admin_percent' => 'required',

                'agent_percent' => 'required'

            ]);

            $admin_percent = $request->input('admin_percent');
            $agent_percent = $request->input('agent_percent');

            WithdrawPercent::create([
                'admin_percent' => $admin_percent,
                'agent_percent' => $agent_percent
            ]);

            return redirect()->route('withdrawPercents.index')->with('success', 'Withdraw Percentage filled successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WithdrawPercent  $withdrawPercent
     * @return \Illuminate\Http\Response
     */
    public function show(WithdrawPercent $withdrawPercent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WithdrawPercent  $withdrawPercent
     * @return \Illuminate\Http\Response
     */
    public function edit(WithdrawPercent $withdrawPercent)
    {
        return view('withdrawPercents.edit', compact('withdrawPercent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WithdrawPercent  $withdrawPercent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WithdrawPercent $withdrawPercent)
    {
        $admin_percent = $request->input('admin_percent');
        $agent_percent = $request->input('agent_percent');

        $withdrawPercent->update([
            'admin_percent' => $admin_percent,
            'agent_percent' => $agent_percent
        ]);

        return redirect()->route('withdrawPercents.index')->with('success', 'Withdraw Percentage updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WithdrawPercent  $withdrawPercent
     * @return \Illuminate\Http\Response
     */
    public function destroy(WithdrawPercent $withdrawPercent)
    {
        //
    }
}
