<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ExchangeRequestController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CompletedRequestController;
use App\Http\Controllers\StatisticController;

Route::prefix('auth')->group(function () {
    require_once 'auth.php';
});

Route::resource('currencies', CurrencyController::class)->except([
    'create', 'edit'
]);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/request', [ExchangeRequestController::class, 'store']);
    Route::post('/wallets', [WalletController::class, 'store']);
    Route::get('/request', [ExchangeRequestController::class, 'index']);
    Route::post('exchanges/{exchangeId}/apply', [CompletedRequestController::class, 'approveExchange']);
});

Route::get('statistics/system-fee', [StatisticController::class, 'getSystemFees']);