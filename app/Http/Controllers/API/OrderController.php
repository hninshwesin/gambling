<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderHistoryResourceCollection;
use App\Jobs\OrderOnClickTime;
use App\Models\AppUser;
use App\Models\BIDCompare;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::guard('user-api')->user();
        $app_user = AppUser::find($user->id);

        $request->validate([
            'amount' => 'required',
            'minute' => 'required',
        ]);

        $amount = $request->input('amount');
        $minute = $request->input('minute');
        $stock_rate = 1003.15;

        $order = Order::create([
            'amount' => $amount,
            'minute' => $minute,
            'stock_rate' => $stock_rate,
            'app_user_id' => $app_user->id,
        ]);

        // dispatch(new OrderOnClickTime($order))->delay(now()->addMinutes($minute));
        OrderOnClickTime::dispatch($order)->delay(now()->addMinutes($minute));

        return response()->json(['error_code' => '3', 'order' => $order, 'message' => 'Success']);
    }

    public function order_history()
    {
        $user = Auth::guard('user-api')->user();
        $app_user = AppUser::find($user->id);

        $order = Order::where('app_user_id', $app_user->id)->get();
        // dd($order);
        return (new OrderHistoryResourceCollection($order));
    }
}
