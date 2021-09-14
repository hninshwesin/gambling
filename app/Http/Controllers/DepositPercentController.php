<?php

namespace App\Http\Controllers;

use App\Models\DepositPercent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositPercentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depositPercents = DepositPercent::all();
        return view('depositPercents.index')->with(['depositPercents' => $depositPercents]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('depositPercents.create');
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

                'agent_percent' => 'required',

                'client_percent' => 'required',

            ]);

            $admin_percent = $request->input('admin_percent');
            $agent_percent = $request->input('agent_percent');
            $client_percent = $request->input('client_percent');

            DepositPercent::create([
                'admin_percent' => $admin_percent,
                'agent_percent' => $agent_percent,
                'client_percent' => $client_percent
            ]);

            return redirect()->route('depositPercents.index')->with('success', 'Deposit Percentage filled successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DepositPercent  $depositPercent
     * @return \Illuminate\Http\Response
     */
    public function show(DepositPercent $depositPercent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DepositPercent  $depositPercent
     * @return \Illuminate\Http\Response
     */
    public function edit(DepositPercent $depositPercent)
    {
        return view('depositPercents.edit', compact('depositPercent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DepositPercent  $depositPercent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DepositPercent $depositPercent)
    {
        $admin_percent = $request->input('admin_percent');
        $agent_percent = $request->input('agent_percent');
        $client_percent = $request->input('client_percent');

        $depositPercent->update([
            'admin_percent' => $admin_percent,
            'agent_percent' => $agent_percent,
            'client_percent' => $client_percent
        ]);

        return redirect()->route('depositPercents.index')->with('success', 'Deposit Percentage updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DepositPercent  $depositPercent
     * @return \Illuminate\Http\Response
     */
    public function destroy(DepositPercent $depositPercent)
    {
        //
    }
}
