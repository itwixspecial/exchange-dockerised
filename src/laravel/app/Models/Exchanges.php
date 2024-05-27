<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchanges extends Model
{
    use HasFactory;

    protected $table = 'exchange_requests';

    protected $fillable = [
        'user_id',
        'currency_from_id',
        'currency_to_id',
        'amount_from',
        'amount_to',
        'commission',
        'availability',
    ];

    protected $casts = [
        'availability' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currencyFrom()
    {
        return $this->belongsTo(Currency::class, 'currency_from_id');
    }

    public function currencyTo()
    {
        return $this->belongsTo(Currency::class, 'currency_to_id');
    }
}
