<?php

namespace App\Http\Resources;

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
            'app_user_id' => $this->app_user_id,
            'amount' => $this->amount,
            'minute' => $this->minute,
            'stock_rate' => $this->stock_rate,
        ];
    }
}
