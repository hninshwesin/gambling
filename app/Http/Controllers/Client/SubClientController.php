<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SubClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('client')->user();
        $client = Client::find($user->id);
        $clients = Client::where('parent_client_id', $client->id)->get();
        return view('clients.sub_client_register')->with(['client' => $client, 'clients' => $clients]);
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
        $user = Auth::guard('client')->user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'email|required|string|max:255|unique:clients',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $name = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        $sub_client = Client::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'agent_id' => $user->agent_id,
            'parent_client_id' => $user->id,
        ]);

        $client = Client::find($user->id);
        if ($client->have_sub_client == 0) {
            $client->have_sub_client = 1;
            $client->save();
        }

        // return redirect()->intended('/agent/home');
        return redirect()->route('sub_client_register.index')->with('success', 'Sub Client created successfully');
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
