<?php

namespace App\Console;

use App\Events\GoldPriceSend;
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
        $schedule->call(function () {
            for ($second = 1; $second <= 86400; $second++) {
                $response = Http::withHeaders(['x-access-token' => 'goldapi-aaqdoetkunu1104-io'])
                    ->accept('application/json')
                    ->get("https://www.goldapi.io/api/XAU/USD");

                $raw = $response->getBody()->getContents();
                $json_raw = json_decode($raw);
                $timestamp = $json_raw->timestamp;
                $price = $json_raw->price;
                $raw_data = RawGoldAPI::create([
                    'body' => $raw,
                    'timestamp' => $timestamp,
                    'price' => $price,
                ]);

                $api = json_decode($response);
                // $current_timestamp = $api->timestamp;
                // var_dump('hello' . $current_timestamp);
                // $previous_timestamp1 = Carbon::createFromTimestamp($current_timestamp)->subSecond()->timestamp;
                // $previous_timestamp2 = Carbon::createFromTimestamp($current_timestamp)->subSecond(2)->timestamp;

                // $raw_data_timestamp = RawGoldAPI::where('timestamp', '<', $current_timestamp)->last();
                // dd($raw_data_timestamp);

                // if ($raw_data_timestamp) {

                $last_record = GoldAPI::latest()->first();
                $goldapi_data = GoldAPI::create([
                    'open_price' => $last_record->close_price,
                    'high_price' => $api->high_price,
                    'low_price' => $api->low_price,
                    'close_price' => $api->price,
                    'timestamp' => $api->timestamp
                ]);
                $goldapi = [
                    'x' => (int) ($goldapi_data->timestamp . '000'),
                    'y' => [$goldapi_data->open_price, $goldapi_data->high_price, $goldapi_data->low_price, $goldapi_data->close_price]
                ];


                broadcast(new GoldPriceSend($goldapi));
                // }
            }
        });
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
