<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AppUser;
use App\Models\Client;
use App\Models\GenerateCode;
use App\Models\TotalBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AppUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agent = Auth::guard('agent')->user();
        $clients = Client::where('agent_id', $agent->id)->get();
        return view('agents.app_users.index')->with(['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agents.app_users.create');
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

        $validatedData = $request->validate([
            'phone_number' => 'required|numeric|unique:clients',
            'email' => 'email|required|string|max:255|unique:clients',
            'password' => 'required|string|min:6|confirmed',
            'code' => 'required',
        ]);

        $phone_number = $request->input('phone_number');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $input_code = $request->input('code');

        $code = GenerateCode::where('code', $input_code)->first();

        if ($code) {
            if ($code->status == 0) {
                $Client = Client::create([
                    'phone_number' => $phone_number,
                    'email' => $email,
                    'password' => $password,
                    'agent_id' => $user->id,
                    'parent_client_id' => 0
                ]);

                TotalBalance::create([
                    'client_id' => $Client->id,
                    'total_balance' => 0,
                    'wallet_balance' => 0
                ]);

                if ($agent->have_client == 0) {
                    $agent->have_client = 1;
                    $agent->save();
                }

                $code->status = 1;
                $code->save();
                // return redirect()->intended('/agent/home');
                return redirect()->route('app_user.index')->with('success', 'Client created successfully');
            } else {
                return redirect()->back()->with('error', 'Code already used!');
            }
        } else {
            return redirect()->back()->with('error', 'The registration code that you entered is not correct!');
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
