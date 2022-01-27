<?php

namespace App\Http\Controllers\API;

use App\Events\GoldPriceSend;
use App\Http\Controllers\Controller;
use App\Models\GoldAPI;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ServiceStartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = new DateTime();

        $real = $date->format('Y-m-d H:i:s');
        $current_date = Carbon::now();

        // $formatted_date = $current_date->format('Y-m-d H:i:s');
        $client = Auth::guard('client-api')->user();
        if ($client) {
            $goldapi_data = GoldAPI::where('created_at',  '<', $current_date)->orderBy('id', 'desc')->limit(3)->get()->reverse();
            $high_price = $goldapi_data->max('high_price');
            $low_price = $goldapi_data->min('low_price');
            // dd($goldapi_data);
            $goldapi = [];
            // $chart_format = [];
            $numItems = count($goldapi_data);
            $i = 0;
            foreach ($goldapi_data as $data) {
                $result = [
                    'x' => (int) ($data->timestamp . '000'),
                    'y' => [$data->open_price, $data->high_price, $data->low_price, $data->close_price]
                ];
                array_push($goldapi, $result);
                if (++$i === $numItems) {
                    array_push($goldapi, [
                        'x' => (int) ($current_date->startOfMinute()->timestamp . '000'),
                        'y' => [$data->open_price, $data->high_price, $data->low_price, $data->close_price]
                    ]);
                }
            }

            // array_push($goldapi, [
            //     'x' => (int) ($current_date->startOfMinute()->timestamp . '000'),
            //     'y' => [$data->open_price, $data->high_price, $data->low_price, $data->close_price]
            // ]);

            // array_push($goldapi, [
            //     'x' => (int) ($current_date->startOfMinute()->timestamp . '000'),
            //     'y' => [0, 0, 0, 0]
            // ]);
            // array_push($chart_format, $goldapi_data);
            // broadcast(new GoldPriceSend($goldapi));

            return response()->json(['data' => $goldapi, 'high_price' => $high_price, 'low_price' => $low_price]);
        } else {
            return response()->json(['error_code' => '1', 'message' => 'You don\'t have access']);
        }
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
        //
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

    // public function test()
    // {
    //     $goldapi = '[{"x":1638958571000,"y":[1784.32,1791.66,1783.7,1789.36]}]';

    //     broadcast(new GoldPriceSend($goldapi));
    // }
}
