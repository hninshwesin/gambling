<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AgentRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('web')->user();
        $admin = User::find($user->id);
        $agents = Agent::where('user_id', $admin->id)->get();
        return view('agent_register')->with(['admin' => $admin, 'agents' => $agents]);
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
        $user = Auth::guard('web')->user();
        $admin = User::find($user->id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'email|required|string|max:255|unique:agents',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $name = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        $agent = Agent::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'total_balance' => 0,
            'user_id' => $user->id
        ]);

        if ($admin->have_agent == 0) {
            $admin->have_agent = 1;
            $admin->save();
        }

        // return redirect()->intended('/home');
        return redirect()->route('agent_register.index')->with('success', 'Agent created successfully');
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
