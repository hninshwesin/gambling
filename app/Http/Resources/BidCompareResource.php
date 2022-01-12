<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BidCompareResource extends JsonResource
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
            'order_id' => $this->order_id,
            'amount' => $this->amount,
            'start_rate' => $this->start_rate,
            'end_rate' => $this->end_rate,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'error_code' => 0
        ];
    }
}
