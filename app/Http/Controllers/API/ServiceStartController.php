<?php

namespace App\Http\Controllers\API;

use App\Events\GoldPriceSend;
use App\Http\Controllers\Controller;
use App\Models\GoldAPI;
use App\Models\RawGoldAPI;
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
        $sub_date =  $date->modify('-10 seconds');

        $formatted_date = $sub_date->format('Y-m-d H:i:s');
        $client = Auth::guard('client-api')->user();
        if ($client) {
            $goldapi = GoldAPI::where('created_at',  '>', $formatted_date)->get();
            $goldapi_data = [];
            // $chart_format = [];
            foreach ($goldapi as $data) {
                $result = [
                    'x' => (int) ($data->timestamp . '000'),
                    'y' => [$data->open_price, $data->high_price, $data->low_price, $data->close_price]
                ];
                array_push($goldapi_data, $result);
            }
            // array_push($chart_format, $goldapi_data);

            return response()->json(['data' => $goldapi_data]);
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

    public function test()
    {
        $goldapi = '[{"x":1638958571000,"y":[1784.32,1791.66,1783.7,1789.36]}]';

        broadcast(new GoldPriceSend($goldapi));
    }
}
