<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderHistoryResourceCollection;
use App\Jobs\OrderOnClickTime;
use App\Models\AppUser;
use App\Models\BIDCompare;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::guard('client-api')->user();
        $app_user = Client::find($user->id);

        // $validatedData = $request->validate([
        //     'amount' => 'required|integer',
        //     'minute' => 'required',
        // ]);

        // if (!$validatedData) {
        //     return response()->json(['error_code' => '1', 'message' => 'Field is required'],  422);
        // }

        $validator = Validator::make($request->all(), [

            'amount' => 'required|integer',

            'minute' => 'required',
        ]);

        $amount = $request->input('amount');
        $minute = $request->input('minute');

        if ($validator->fails()) {
            return response()->json(['error_code' => '1', 'message' => $validator->messages()],  422);
        } else {
            $response = Http::withHeaders(['x-access-token' => 'goldapi-aaqdoetkunu1104-io'])
                ->accept('application/json')
                ->get("https://www.goldapi.io/api/XAU/USD");

            $stock_rate = json_decode($response);

            $order = Order::create([
                'amount' => $amount,
                'minute' => $minute,
                'stock_rate' => $stock_rate->price,
                'client_id' => $app_user->id,
            ]);

            // dispatch(new OrderOnClickTime($order))->delay(now()->addMinutes($minute));
            OrderOnClickTime::dispatch($order)->delay(now()->addMinutes($minute));

            return response()->json(['error_code' => '0', 'order' => $order, 'message' => 'Success']);
        }
    }

    public function order_history()
    {
        $user = Auth::guard('client-api')->user();
        $app_user = Client::find($user->id);

        $order = Order::where('client_id', $app_user->id)->get();

        return (new OrderHistoryResourceCollection($order));
    }
}
