<?php

namespace App\Jobs;

use App\Models\BIDCompare;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderOnClickTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $response;
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
        $end_rate = 1002.44;

        if ($start_rate < $end_rate) {
            // var_dump($start_rate);
            // var_dump($end_rate);
            // var_dump($this->order->amount);
            var_dump('win');

            $BID = BIDCompare::create([
                'order_id' => $this->order->id,
                'amount' => $this->order->amount,
                'start_rate' => $start_rate,
                'end_rate' => $end_rate,
                'status' => 1,
            ]);

            // $this->respond('Success');
            // $this->response = $BID;

            return response()->json(['error_code' => '0', 'order' => $this->order, 'BID' => $BID, 'message' => 'Success']);
        } elseif ($start_rate > $end_rate) {
            var_dump('loss');

            $BID = BIDCompare::create([
                'order_id' => $this->order->id,
                'amount' => $this->order->amount,
                'start_rate' => $start_rate,
                'end_rate' => $end_rate,
                'status' => 2,
            ]);

            // $this->response = $BID;
            return response()->json(['error_code' => '0', 'BID' => $BID, 'message' => 'Success']);
        } elseif ($start_rate == $end_rate) {
            var_dump('stable');

            $BID = BIDCompare::create([
                'order_id' => $this->order->id,
                'amount' => $this->order->amount,
                'start_rate' => $start_rate,
                'end_rate' => $end_rate,
                'status' => 0,
            ]);

            // $this->response = $BID;
            return response()->json(['error_code' => '0', 'BID' => $BID, 'message' => 'Success']);
        }
    }

    // public function getResponse()
    // {
    //     return $this->response;
    // }
}
