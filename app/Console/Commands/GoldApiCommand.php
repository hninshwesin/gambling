<?php

namespace App\Console\Commands;

use App\Events\GoldPriceSendEverySecond;
use App\Models\BidPrice;
use App\Models\GoldAPI;
use App\Models\RawGoldAPI;
use App\Models\SecondGoldAPI;
use Carbon\Carbon;
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

            $bid_price = BidPrice::create([
                'bid_price' => $raw_data->price,
                'timestamp' => $raw_data->timestamp
            ]);

            $current = Carbon::now();
            $now = Carbon::now();
            $start_minute = $now->startOfMinute()->toDateTimeString();
            $end_minute = $now->endOfMinute()->toDateTimeString();

            $data = BidPrice::whereBetween('created_at', [$start_minute, $end_minute])->get();

            $open = $data->first();
            $close = $data->last();
            $low_price = $data->min('bid_price');
            $high_price = $data->max('bid_price');
            $timestamp = $current->timestamp;

            // var_dump('open price is ' . $open->bid_price);
            // var_dump('close price is' . $close->bid_price);
            // var_dump('low_price is ' . $low_price);
            // var_dump('high price is ' . $high_price);
            // var_dump('timestamp is ' . $timestamp);

            $goldapi_data = SecondGoldAPI::create([
                'open_price' => $open->bid_price,
                'high_price' => $high_price,
                'low_price' => $low_price,
                'close_price' => $close->bid_price,
                'timestamp' => $timestamp,
                'start_time' => $start_minute,
                'end_time' => $end_minute
            ]);
            $everysecond_goldapi = [
                'x' => (int) (Carbon::parse($goldapi_data->start_time)->timestamp . '000'),
                'y' => [$goldapi_data->open_price, $goldapi_data->high_price, $goldapi_data->low_price, $goldapi_data->close_price]
            ];

            broadcast(new GoldPriceSendEverySecond($everysecond_goldapi));
        }
        return 0;
    }
}
