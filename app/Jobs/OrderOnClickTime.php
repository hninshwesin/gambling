<?php

namespace App\Jobs;

use App\Models\BIDCompare;
use App\Models\Order;
use App\Models\TotalBalance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderOnClickTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start_rate = $this->order->stock_rate;
        $response = Http::withHeaders(['x-access-token' => 'goldapi-aaqdoetkunu1104-io'])
            ->accept('application/json')
            ->get("https://www.goldapi.io/api/XAU/USD");
        $end_rate = json_decode($response);

        // Log::info('Log' . json_encode($this->order));

        if ($start_rate < $end_rate->price) {
            // var_dump('win');
            $status = 1;
        } elseif ($start_rate > $end_rate->price) {
            // var_dump('loss');
            $status = 2;
        } elseif ($start_rate == $end_rate->price) {
            // var_dump('stable');
            $status = 0;
        }
        $BID = BIDCompare::create([
            'order_id' => $this->order->id,
            'amount' => $this->order->amount,
            'start_rate' => $start_rate,
            'end_rate' => $end_rate->price,
            'status' => $status,
        ]);

        if ($BID->status == 1) {
            $client = TotalBalance::where('client_id', $this->order->client_id)->first();
            if ($client) {
                $client->total_balance += $this->order->amount;
                $client->wallet_balance += $this->order->amount;
                $client->save();
            }
        } elseif ($BID->status == 2) {
            $client = TotalBalance::where('client_id', $this->order->client_id)->first();
            if ($client) {
                $client->total_balance -= $this->order->amount;
                $client->wallet_balance -= $this->order->amount;
                $client->save();
            }
        }
    }
}
