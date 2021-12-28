<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TotalBalaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'app_user_id' => $this->client_id,
            'total_balance' => $this->total_balance,
            'wallet_balance' => $this->wallet_balance,
            'phone_number' => $this->client->phone_number,
            'email' => $this->client->email,
            'status' => 0,
            'error_code' => 0
        ];
    }
}
