<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'symb',
    ];

    public function exchangeRequestsFrom()
    {
        return $this->hasMany(ExchangeRequest::class, 'currency_from_id');
    }

    public function exchangeRequestsTo()
    {
        return $this->hasMany(ExchangeRequest::class, 'currency_to_id');
    }

}
