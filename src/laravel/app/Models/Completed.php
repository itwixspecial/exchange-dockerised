<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Completed extends Model
{
    use HasFactory;

    protected $table = 'completed';

    protected $guarded = [];

    // Зв'язок з ExchangeRequest
    public function exchangeRequest()
    {
        return $this->belongsTo(ExchangeRequest::class);
    }

    // Зв'язок з User (applier)
    public function applier()
    {
        return $this->belongsTo(User::class, 'applier_id');
    }

    public function walletAcceptorFrom()
    {
        return $this->belongsTo(Wallet::class, 'wallet_from_id');
    }

    public function walletAcceptorTo()
    {
        return $this->belongsTo(Wallet::class, 'wallet_from_id');
    }

    public function walletCreatorTo()
    {
        return $this->belongsTo(Wallet::class, 'wallet_to_id');
    }

    public function walletCreatorFrom()
    {
        return $this->belongsTo(Wallet::class, 'wallet_to_id');
    }
}
