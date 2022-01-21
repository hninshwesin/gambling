<?php

namespace App\Console\Commands;

use App\Models\GoldAPI;
use App\Models\RawGoldAPI;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GoldApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gold:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get gold by second';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
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

            $goldapi_data = GoldAPI::create([
                'open_price' => $api->prev_close_price,
                'high_price' => $api->high_price,
                'low_price' => $api->low_price,
                'close_price' => $api->price,
                'timestamp' => $api->timestamp
            ]);
            $goldapi = [
                'x' => (int) ($goldapi_data->timestamp . '000'),
                'y' => [$goldapi_data->open_price, $goldapi_data->high_price, $goldapi_data->low_price, $goldapi_data->close_price]
            ];


            // var_dump($goldapi);

            //                broadcast(new GoldPriceSend($goldapi));
            // }
        }
        return 0;
    }
}
