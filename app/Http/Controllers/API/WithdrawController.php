<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\WithdrawResourceCollection;
use App\Models\Client;
use App\Models\Withdraw;
use App\Models\WithdrawPercent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    public function withdraw_history()
    {
        $user = Auth::guard('client-api')->user();
        $app_user = Client::find($user->id);

        if ($app_user) {
            $withdraws = Withdraw::where('client_id', $app_user->id)->orderBy('id', 'desc')->get();

            return new WithdrawResourceCollection($withdraws);
        }
    }

    public function withdraw_request(Request $request)
    {
        $request->validate([
            'amount' => 'required'
        ]);
        $withdraw_amount = $request->input('amount');
        $description = $request->input('description');

        $user = Auth::guard('client-api')->user();
        $app_user = Client::find($user->id);

        if ($app_user) {
            if ($app_user->total_balances->total_balance >= $withdraw_amount) {
                $percent = WithdrawPercent::orderBy('id', 'DESC')->first();
                $admin_fee = $percent->admin_percent;
                $agent_fee = $percent->agent_percent;

                $fee = $admin_fee + $agent_fee;
                // $admin_amount = ($withdraw_amount * $admin_fee) / 100;
                // $agent_amount = ($withdraw_amount * $agent_fee) / 100;
                $remove_amount = ($withdraw_amount * $fee) / 100;
                $final_amount = $withdraw_amount - $remove_amount;

                $withdraw = Withdraw::create([
                    'client_id' => $app_user->id,
                    'amount' => $withdraw_amount,
                    'fee' => $fee,
                    'final_amount' => $final_amount,
                    'description' => $description,
                    'approve_status' => 0
                ]);

                return response()->json(['error_code' => '0', 'message' => 'Request to withdraw money done successfully,Super Option Admins will contact soon.']);
            } else {
                return response()->json(['error_code' => '1', 'message' => 'Your requested amount is not sufficient']);
            }
        } else {
            return response()->json(['error_code' => '1', 'message' => 'Something went wrong,Please Sign in again!']);
        }
    }
}
