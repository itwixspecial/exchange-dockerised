<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Http\Requests\CreateWalletRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    public function store(CreateWalletRequest $request): JsonResponse
    {
        $user = Auth::user();

        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->currency_id = $request->currency_id;
        $wallet->balance = 0; // Початковий баланс 0
        $wallet->save();

        return response()->json(['message' => 'Wallet created successfully'], 201);
    }
}
