<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AppUser;
use App\Models\Client;
use App\Models\GenerateCode;
use App\Models\TotalBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'phone_number' => 'required|numeric|unique:clients',
            'email' => 'email|string|max:255|required|unique:clients',
            'password' => 'required|string|min:6|confirmed',
            'code' => 'required'
        ]);

        $phone_number = $request->input('phone_number');
        $email = $request->input('email');
        $password = bcrypt($request->password);
        $input_code = $request->input('code');

        $code = GenerateCode::where('code', $input_code)->first();

        if ($code) {
            if ($code->status == 0) {
                if ($code->generate_type == 0) {
                    $client = Client::create([
                        'phone_number' => $phone_number,
                        'email' => $email,
                        'password' => $password,
                        'agent_id' => $code->agent_id,
                        'parent_client_id' => 0
                    ]);

                    TotalBalance::create([
                        'client_id' => $client->id,
                        'total_balance' => 0,
                        'wallet_balance' => 0,
                    ]);

                    $agent = Agent::find($code->agent_id);

                    if ($agent->have_client == 0) {
                        $agent->have_client = 1;
                        $agent->save();
                    }

                    $code->status = 1;
                    $code->save();

                    $accessToken = $client->createToken('authToken')->accessToken;

                    return response()->json(['error_code' => '0', 'app_user' => $client, 'access_token' => $accessToken, 'message' => 'Register successfully']);
                } elseif ($code->generate_type == 1) {
                    $parent_client = Client::find($code->client_id);
                    $client = Client::create([
                        'phone_number' => $phone_number,
                        'email' => $email,
                        'password' => $password,
                        'agent_id' => $parent_client->agent_id,
                        'parent_client_id' => $parent_client->id
                    ]);

                    TotalBalance::create([
                        'client_id' => $client->id,
                        'total_balance' => 0,
                        'wallet_balance' => 0,
                    ]);

                    $agent = Agent::find($parent_client->agent_id);

                    if ($agent->have_client == 0) {
                        $agent->have_client = 1;
                        $agent->save();
                    }

                    $code->status = 1;
                    $code->save();

                    $accessToken = $client->createToken('authToken')->accessToken;

                    return response()->json(['error_code' => '0', 'app_user' => $client, 'access_token' => $accessToken, 'message' => 'Register successfully']);
                }
            } else {
                return response()->json(['error_code' => '1', 'message' => 'Code already used'], 422);
            }
        } else {
            return response()->json(['error_code' => '1', 'message' => 'The registration code that you entered is not correct!'], 422);
        }
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (Auth::guard('client')->attempt($loginData)) {
            $accessToken = Auth::guard('client')->user()->createToken('authToken')->accessToken;

            return response()->json(['error_code' => '0', 'app_user' => Auth::guard('client')->user(), 'access_token' => $accessToken, 'message' => 'Login successfully']);
        } else {
            return response()->json(['error_code' => '1', 'message' => 'Invalid Credentials'],  403);
        }
    }
}
