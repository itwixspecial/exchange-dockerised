<?php

namespace App\Services;

use App\Models\Exchanges;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class ApplyRequestValidationService
{

    public function validateUserCanApplyAndBalance(int $exchangeId, int $userId, int $currentyToId, int $amountTo ): ?string
    {
        if (!$this->validateUserCanApply($exchangeId)) {
            return 'User is not allowed to apply for this request.';
        }

        if (!$this->validateUserBalance($userId, $currentyToId, $amountTo)) {
            return 'Insufficient balance to apply for this request.';
        }

        return null; // No errors
    }

    private function validateUserCanApply(int $requestId): bool
    {
        $request = Exchanges::findOrFail($requestId);

        return $request->user_id !== Auth::id() && $request->availability ;
    }

    private function validateUserBalance(int $userId, int $currencyId, float $amount): bool
    {
        $wallet = Wallet::where('user_id', $userId)
            ->where('currency_id', $currencyId)
            ->first();

        return $wallet && $wallet->balance >= $amount;
    }

    public function findUserWalletId(int $userId, int $currencyId): ?int
    {
        return Wallet::where('user_id', $userId)
                     ->where('currency_id', $currencyId)
                     ->value('id');
    }

}
