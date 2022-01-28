<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawResource extends JsonResource
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
            'client_id' => $this->client_id,
            'client_phone_number' => $this->client->phone_number,
            'client_email' => $this->client->email,
            'amount' => $this->amount,
            'fee' => $this->fee,
            'final_amount' => $this->final_amount,
            'description' => $this->description,
            'approve_status' => $this->approve_status,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
