<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderHistoryResource;
use App\Http\Resources\OrderHistoryResourceCollection;
use App\Jobs\OrderOnClickTime;
use App\Models\AppUser;
use App\Models\BIDCompare;
use App\Models\Client;
use App\Models\Order;
use App\Models\TotalBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function buy(Request $request)
    {
        $user = Auth::guard('client-api')->user();
        $client = Client::find($user->id);

        $request->validate([
            'amount' => 'required',
            'minute' => 'required',
        ]);

        // if (!$validatedData) {
        //     return response()->json(['error_code' => '1', 'message' => 'Field is required'],  422);
        // }

        // $validator = Validator::make($request->all(), [

        //     'amount' => 'required|integer',

        //     'minute' => 'required',
        // ]);

        $amount = $request->input('amount');
        $minute = $request->input('minute');

        $wallet = TotalBalance::where('client_id', $client->id)->first();
        $balance = $wallet->total_balance;

        if ($balance > $amount) {
            $response = Http::withHeaders(['x-access-token' => 'goldapi-aaqdoetkunu1104-io'])
                ->accept('application/json')
                ->get("https://www.goldapi.io/api/XAU/USD");

            $stock_rate = json_decode($response);

            $order = Order::create([
                'amount' => $amount,
                'minute' => $minute,
                'stock_rate' => $stock_rate->price,
                'client_id' => $client->id,
                'bid_status' => 0,
            ]);

            // dispatch(new OrderOnClickTime($order))->delay(now()->addMinutes($minute));
            OrderOnClickTime::dispatch($order)->delay(now()->addMinutes($minute));

            return response()->json(['error_code' => '0', 'order' => $order, 'message' => 'Your Order placed successfully']);
        } elseif ($balance < $amount) {
            return response()->json(['error_code' => '1', 'message' => 'Your balance is not sufficient'], 422);
        }
    }

    public function sell(Request $request)
    {
        $user = Auth::guard('client-api')->user();
        $client = Client::find($user->id);

        $request->validate([
            'amount' => 'required',
            'minute' => 'required',
        ]);

        $amount = $request->input('amount');
        $minute = $request->input('minute');

        $wallet = TotalBalance::where('client_id', $client->id)->first();
        $balance = $wallet->total_balance;

        if ($balance > $amount) {
            $response = Http::withHeaders(['x-access-token' => 'goldapi-aaqdoetkunu1104-io'])
                ->accept('application/json')
                ->get("https://www.goldapi.io/api/XAU/USD");

            $stock_rate = json_decode($response);

            $order = Order::create([
                'amount' => $amount,
                'minute' => $minute,
                'stock_rate' => $stock_rate->price,
                'client_id' => $client->id,
                'bid_status' => 1,
            ]);

            // dispatch(new OrderOnClickTime($order))->delay(now()->addMinutes($minute));
            OrderOnClickTime::dispatch($order)->delay(now()->addMinutes($minute));

            return response()->json(['error_code' => '0', 'order' => $order, 'message' => 'Your Order placed successfully']);
        } elseif ($balance < $amount) {
            return response()->json(['error_code' => '1', 'message' => 'Your balance is not sufficient'], 422);
        }
    }

    public function order_history()
    {
        $user = Auth::guard('client-api')->user();
        $app_user = Client::find($user->id);

        $order = Order::where('client_id', $app_user->id)->orderBy('id', 'desc')->get();

        return (new OrderHistoryResourceCollection($order));
    }

    public function last_order_history()
    {
        $user = Auth::guard('client-api')->user();
        $app_user = Client::find($user->id);

        $order = Order::where('client_id', $app_user->id)->orderBy('id', 'desc')->first();

        $orders = collect([$order]);

        return OrderHistoryResource::collection($orders);
    }
}
