<?php

namespace App\Http\Resources;

use App\Models\BIDCompare;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'app_user_id' => $this->client_id,
            'amount' => $this->amount,
            'minute' => $this->minute,
            'stock_rate' => $this->stock_rate,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'bid_compare' => new BidCompareResource(BIDCompare::where('order_id', $this->id)->first()),
        ];
    }
}
