<?php

namespace App\Services;

use App\Models\Wallet;

class ExchangeRequestValidationService
{
    public function validateUserWallets(int $userId, int $currencyFromId, int $currencyToId): bool
    {
        $userWallets = Wallet::where('user_id', $userId)
            ->whereIn('currency_id', [$currencyFromId, $currencyToId])
            ->get();

        $fromWallet = $userWallets->firstWhere('currency_id', $currencyFromId);
        $toWallet = $userWallets->firstWhere('currency_id', $currencyToId);

        return $fromWallet && $toWallet;
    }

    public function validateSufficientBalance(int $userId, int $currencyFromId, float $amountFrom): bool
    {
        $fromWallet = Wallet::where('user_id', $userId)
            ->where('currency_id', $currencyFromId)
            ->first();

        return $fromWallet && $fromWallet->balance >= $amountFrom;
    }
    
}
