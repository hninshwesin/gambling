<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\TotalBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'phone_number' => 'required|numeric|unique:app_users',
            'email' => 'email|required|unique:app_users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = AppUser::create($validatedData);

        TotalBalance::create([
            'app_user_id' => $user->id,
            'total_balance' => 0,
        ]);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(['error_code' => '0', 'app_user' => $user, 'access_token' => $accessToken, 'message' => 'Register successfully']);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!Auth::guard('user')->attempt($loginData)) {
            return response()->json(['error_code' => '1', 'message' => 'Invalid Credentials'],  403);
        }

        $accessToken = Auth::guard('user')->user()->createToken('authToken')->accessToken;

        return response()->json(['error_code' => '0', 'app_user' => Auth::guard('user')->user(), 'access_token' => $accessToken, 'message' => 'Login successfully']);
    }
}
