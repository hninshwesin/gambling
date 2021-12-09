<?php

namespace App\Console;

use App\Events\GoldPriceSend;
use App\Models\GoldAPI;
use App\Models\RawGoldAPI;
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
                RawGoldAPI::create([
                    'body' => $raw
                ]);

                $api = json_decode($response);

                $goldapi_data = GoldAPI::create([
                    'open_price' => $api->open_price,
                    'high_price' => $api->high_price,
                    'low_price' => $api->low_price,
                    'close_price' => $api->price,
                    'timestamp' => $api->timestamp
                ]);

                // dd($goldapi_data);
                $result = [
                    'x' => (int) ($goldapi_data->timestamp . '000'),
                    'y' => [$goldapi_data->open_price, $goldapi_data->high_price, $goldapi_data->low_price, $goldapi_data->close_price]
                ];


                broadcast(new GoldPriceSend($result));
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
