<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepositResourceCollection;
use App\Models\Client;
use App\Models\Deposit;
use App\Models\DepositPercent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function deposit_history()
    {
        $user = Auth::guard('client-api')->user();
        $app_user = Client::find($user->id);

        if ($app_user) {
            $deposits = Deposit::where('client_id', $app_user->id)->orderBy('id', 'desc')->get();

            return new DepositResourceCollection($deposits);
        }
    }

    public function deposit_request(Request $request)
    {
        $request->validate([
            'amount' => 'required'
        ]);

        $deposit_amount = $request->input('amount');
        $description = $request->input('description');
        // dd($deposit_amount, $description);
        $user = Auth::guard('client-api')->user();
        $app_user = Client::find($user->id);

        if ($app_user) {
            if ($app_user->parent_client_id == 0) {
                $percent = DepositPercent::orderBy('id', 'DESC')->first();
                $admin_fee = $percent->admin_percent;
                $agent_fee = $percent->agent_percent;

                $fee = $admin_fee + $agent_fee;
                // $admin_amount = ($deposit_amount * $admin_fee) / 100;
                // $agent_amount = ($deposit_amount * $agent_fee) / 100;
                $remove_amount = ($deposit_amount * $fee) / 100;
                $final_amount = $deposit_amount - $remove_amount;

                $deposit = Deposit::create([
                    'client_id' => $app_user->id,
                    'amount' => $deposit_amount,
                    'fee' => $fee,
                    'final_amount' => $final_amount,
                    'description' => $description,
                    'approve_status' => 0,
                    'agent_id' => $app_user->agent_id
                ]);
                return response()->json(['error_code' => '0', 'message' => 'Deposit Request done successfully,Super Option Admins will contact soon.']);
            } elseif ($app_user->parent_client_id != 0) {
                $percent = DepositPercent::orderBy('id', 'DESC')->first();
                $admin_fee = $percent->admin_percent;
                $agent_fee = $percent->agent_percent;
                $client_fee = $percent->client_percent;

                $fee = $admin_fee + $agent_fee + $client_fee;
                // $admin_amount = ($deposit_amount * $admin_fee) / 100;
                // $agent_amount = ($deposit_amount * $agent_fee) / 100;
                // $client_amount = ($deposit_amount * $client_fee) / 100;
                $remove_amount = ($deposit_amount * $fee) / 100;
                $final_amount = $deposit_amount - $remove_amount;

                $deposit = Deposit::create([
                    'client_id' => $app_user->id,
                    'amount' => $deposit_amount,
                    'fee' => $fee,
                    'final_amount' => $final_amount,
                    'description' => $description,
                    'approve_status' => 0,
                    'agent_id' => $app_user->agent_id
                ]);
                return response()->json(['error_code' => '0', 'message' => 'Deposit Request done successfully,Super Option Admins will contact soon.']);
            }
        } else {
            return response()->json(['error_code' => '1', 'message' => 'Something went wrong,Please Sign in again!']);
        }
    }
}
