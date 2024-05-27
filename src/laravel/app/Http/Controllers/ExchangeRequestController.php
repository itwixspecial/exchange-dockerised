<?php

namespace App\Http\Controllers;

use App\Models\Exchanges;
use \App\Facades\CommissionFacade;
use App\Http\Requests\CreateExchangeRequest;
use App\Http\Resources\ExchangeRequestResource;
use App\Services\ExchangeRequestValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeRequestController extends Controller
{   
    public function index(): JsonResource
    {   
        $exchangeRequests = Exchanges::all();
        return ExchangeRequestResource::collection($exchangeRequests);
    }

    public function store(CreateExchangeRequest $request, ExchangeRequestValidationService $validationService): ExchangeRequestResource | JsonResponse
    {   
        // Перевірка прав користувача
        if (!auth()->user()->can('create_request')) {
            return response()->json(['error' => 'Acces denied!'], 403);
        }

        $validatedData = $request->validated();

        // Перевірка наявності гаманців користувача перед створенням запиту
        if (!$validationService->validateUserWallets(auth()->id(), $validatedData['currency_from_id'], $validatedData['currency_to_id'])) {
            return response()->json(['error' => 'Missing wallet!'], 400);
        }
        // Перевірка достатнього балансу на гаманці перед створенням запиту
        if (!$validationService->validateSufficientBalance(auth()->id(), $validatedData['currency_from_id'], $validatedData['amount_from'])) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        // Розрахунок комісії
        $commission = CommissionFacade::calculateCommission($validatedData['amount_from']);

        // Створення запиту обміну з комісією, можна винести окремо але в даній глобальній задачі не актуально
        $exchangeRequest = Exchanges::create([
            'user_id' => auth()->id(),
            'currency_from_id' => $validatedData['currency_from_id'],
            'currency_to_id' => $validatedData['currency_to_id'],
            'amount_from' => $validatedData['amount_from'],
            'amount_to' => $validatedData['amount_to'],
            'commission' => $commission,
            'availability' => true,
        ]);

        return new ExchangeRequestResource($exchangeRequest);
    }

    public function update(CreateExchangeRequest $request, int $id): ExchangeRequestResource | JsonResponse
    {
        $exchangeRequest = Exchanges::findOrFail($id);

        // Перевірка, чи поточний користувач є власником
        if ($exchangeRequest->user_id !== auth()->id()) {
            return response()->json(['error' => 'Acces denied'], 403);
        }

        // Оновлення запису
        $validatedData = $request->validated();
        $exchangeRequest->update($validatedData);

        return new ExchangeRequestResource($exchangeRequest);
    }
}
