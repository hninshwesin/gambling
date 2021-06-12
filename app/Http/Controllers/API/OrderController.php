<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\OrderOnClickTime;
use App\Models\AppUser;
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
        $BIDresponse = OrderOnClickTime::dispatch($order)->delay(now()->addMinutes($minute));

        return response()->json($BIDresponse->getResponse());
        // return response()->json(['error_code' => '0', 'BID' => $BIDresponse, 'message' => 'Success']);
    }
}
