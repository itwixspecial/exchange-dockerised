<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'currency_from' => optional($this->currencyFrom)->symb, 
            'currency_to' => optional($this->currencyTo)->symb,
            'amount_from' => $this->amount_from,
            'amount_to' => $this->amount_to,
            'commission' => $this->commission,
            'availability' => $this->availability,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
