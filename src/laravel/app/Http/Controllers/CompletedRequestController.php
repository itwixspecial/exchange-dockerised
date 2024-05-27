<?php

namespace App\Http\Controllers;

use App\Models\Exchanges;
use App\Services\ApplyRequestValidationService;
use App\Services\ApplyRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CompletedRequestController extends Controller
{
    
    private ApplyRequestValidationService $applyRequestValidationService;
    private ApplyRequestService $applyRequestService;

    public function __construct(
        ApplyRequestValidationService $applyRequestValidationService,
        ApplyRequestService $applyRequestService
    ) {
        $this->applyRequestValidationService = $applyRequestValidationService;
        $this->applyRequestService = $applyRequestService;
    }

    public function approveExchange(int $exchangeId): JsonResponse
    {   
        $user_id = auth()->id();

        $exchange = Exchanges::findOrFail($exchangeId);

        //валідація балансу
        $error = $this->applyRequestValidationService->validateUserCanApplyAndBalance($exchangeId, auth()->id(), $exchange->currency_to_id, $exchange->amount_to);
    
        if ($error) {
            return response()->json(['error' => $error], 403);
        } 

        //отримуємо гаманці юзерів
        $walletCreatorFromId = $this->applyRequestValidationService->findUserWalletId($exchange->user_id, $exchange->currency_from_id);
        $walletCreatorToId = $this->applyRequestValidationService->findUserWalletId($exchange->user_id, $exchange->currency_to_id);
        $walletAcceptorFromId = $this->applyRequestValidationService->findUserWalletId($user_id, $exchange->currency_to_id);
        $walletAcceptorToId = $this->applyRequestValidationService->findUserWalletId($user_id, $exchange->currency_from_id);

        if (!$walletCreatorFromId || !$walletCreatorToId || !$walletAcceptorFromId || !$walletAcceptorToId) {
            throw new \Exception('Wallet not found for user.');
        }

        //проводимо обмін по базі
        $this->applyRequestService->createCompletedRequest(
            $exchange->id,
            Auth::id(),
            $walletAcceptorFromId,
            $walletAcceptorToId,
            $walletCreatorFromId,
            $walletCreatorToId
        );

        return response()->json(['message' => 'Request applied successfully.'], 200);
    }
}
