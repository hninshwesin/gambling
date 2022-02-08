<?php

namespace App\Console;

use App\Events\GoldPriceSend;
use App\Models\BidPrice;
use App\Models\GoldAPI;
use App\Models\RawGoldAPI;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('gold:api');
        $schedule->call(function () {
            $start = Carbon::now()->subMinutes('5')->startOfMinute();
            $end = Carbon::now()->subMinute()->endOfMinute();

            $first = BidPrice::where('created_at', '>=', $start)->first();

            if ($first) {

                $data = BidPrice::whereBetween('created_at', [$first->created_at,  $end])->get();

                $open = $data->first();
                $close = $data->last();
                $low_price = $data->min('bid_price');
                $high_price = $data->max('bid_price');

                // var_dump('open price is ' . $open->bid_price);
                // var_dump('close price is' . $close->bid_price);
                // var_dump('low_price is ' . $low_price);
                // var_dump('high price is ' . $high_price);
                // var_dump('timestamp is ' . $timestamp->timestamp);

                $goldapi_data = GoldAPI::create([
                    'open_price' => $open->bid_price,
                    'high_price' => $high_price,
                    'low_price' => $low_price,
                    'close_price' => $close->bid_price,
                    'timestamp' => $start->timestamp,
                    'start_time' => $first->created_at,
                    'end_time' => $end,
                ]);
                $goldapi = [];
                $result = [
                    'x' => (int) ($goldapi_data->timestamp . '000'),
                    'y' => [$goldapi_data->open_price, $goldapi_data->high_price, $goldapi_data->low_price, $goldapi_data->close_price]
                ];
                array_push($goldapi, $result);
                array_push($goldapi, [
                    'x' => (int) ($start->addMinutes(5)->timestamp . '000'),
                    'y' => [$goldapi_data->open_price, $goldapi_data->high_price, $goldapi_data->low_price, $goldapi_data->close_price]
                ]);
                // array_push($goldapi, [
                //     'x' => (int) ($start->addMinute()->timestamp . '000'),
                //     'y' => [0, 0, 0, 0]
                // ]);

                broadcast(new GoldPriceSend($goldapi));
            } else {
                var_dump('no time stamp');
            }

            // for ($second = 1; $second <= 86400; $second++) {
            //     $response = Http::withHeaders(['x-access-token' => 'goldapi-aaqdoetkunu1104-io'])
            //         ->accept('application/json')
            //         ->get("https://www.goldapi.io/api/XAU/USD");

            //     $raw = $response->getBody()->getContents();
            //     $json_raw = json_decode($raw);
            //     $timestamp = $json_raw->timestamp;
            //     $price = $json_raw->price;
            //     $raw_data = RawGoldAPI::create([
            //         'body' => $raw,
            //         'timestamp' => $timestamp,
            //         'price' => $price,
            //     ]);

            //     $bid_price = BidPrice::create([
            //         'bid_price' => $raw_data->price,
            //         'timestamp' => $raw_data->timestamp
            //     ]);

            //     // dd($bid_price->timestamp);

            //     $timestamp = Carbon::createFromTimestamp($bid_price->timestamp)->subMinute(1)->timestamp;
            //     // dd($timestamp);
            //     $sub_timestamp = BidPrice::where('timestamp', '<', $timestamp)->last();
            //     $current_timestamp = BidPrice::where('timestamp', '=', $timestamp)->first();
            //     dd($sub_timestamp);

            //     if ($sub_timestamp) {

            //         $data = BidPrice::whereBetween('timestamp', [$sub_timestamp->timestamp, $current_timestamp->timestamp])->get();
            //         dd($data);

            //         // $last_record = GoldAPI::latest()->first();
            //         $goldapi_data = GoldAPI::create([
            //             'open_price' => $sub_timestamp->price,
            //             'high_price' => $api->high_price,
            //             'low_price' => $api->low_price,
            //             'close_price' => $bid_price->bid_price,
            //             'timestamp' => $bid_price->timestamp
            //         ]);
            //         $goldapi = [
            //             'x' => (int) ($goldapi_data->timestamp . '000'),
            //             'y' => [$goldapi_data->open_price, $goldapi_data->high_price, $goldapi_data->low_price, $goldapi_data->close_price]
            //         ];


            //         broadcast(new GoldPriceSend($goldapi));
            //     }
            // }
        })->everyFiveMinutes();;

        $schedule->command('gold:api');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
