<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthRegisterController;

Route::post('/register', [AuthRegisterController::class, 'register']);
Route::post('/login', [AuthRegisterController::class, 'login']);