<?php

namespace App\Services;

use App\Models\Completed;
use App\Models\Exchanges;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class ApplyRequestService
{
    public function createCompletedRequest(int $exchangeRequestId, int $applierId, int $walletAcceptorFromId, int $walletAcceptorToId, int $walletCreatorFromId, int $walletCreatorToId): Completed
    {
        // Виконуємо транзакцію, щоб зберегти цілісність даних
        DB::transaction(function () use ($exchangeRequestId, $applierId, $walletAcceptorFromId, $walletAcceptorToId, $walletCreatorFromId, $walletCreatorToId) {
            // Знаходимо запит обміну
            $exchangeRequest = Exchanges::findOrFail($exchangeRequestId);

            // Оновлюємо доступність запиту
            $exchangeRequest->availability = false;
            $exchangeRequest->save();

            // Оновлюємо баланси гаманців
            // З балансу гаманця walletCreatorFromId віднімаємо amount_from
            Wallet::where('id', $walletCreatorFromId)->decrement('balance', $exchangeRequest->amount_from);

            // До балансу гаманця walletCreatorToId додаємо amount_to
            Wallet::where('id', $walletCreatorToId)->increment('balance', $exchangeRequest->amount_to);

            // З балансу гаманця walletAcceptorFromId віднімаємо amount_to
            Wallet::where('id', $walletAcceptorFromId)->decrement('balance', $exchangeRequest->amount_to);

            // До балансу гаманця walletAcceptorToId додаємо amount_from - комісія
            $amountWithCommission = $exchangeRequest->amount_from - $exchangeRequest->commission;
            Wallet::where('id', $walletAcceptorToId)->increment('balance', $amountWithCommission);

            // Створюємо запис про завершений запит
            Completed::create([
                'exchange_request_id' => $exchangeRequestId,
                'applier_id' => $applierId,
                'wallet_acceptor_from_id' => $walletAcceptorFromId,
                'wallet_acceptor_to_id' => $walletAcceptorToId,
                'wallet_creator_from_id' => $walletCreatorFromId,
                'wallet_creator_to_id' => $walletCreatorToId,
            ]);
        });

        // Повертаємо завершений запит
        return Completed::where('exchange_request_id', $exchangeRequestId)->first();
    }
}
