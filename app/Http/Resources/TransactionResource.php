<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'transaction_type' => $this->transaction_type,
            'amount' => $this->amount,
            'fee' => $this->fee,
            'date' => $this->date,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
