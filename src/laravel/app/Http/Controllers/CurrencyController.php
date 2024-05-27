<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Currency::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'symb' => 'required|string|unique:currencies|max:255',
        ]);

        $currency = Currency::create($validated);

        return response()->json($currency, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Currency::findOrFail($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $currency = Currency::findOrFail($id);

        $validated = $request->validate([
            'symb' => 'required|string|unique:currencies|max:255',
        ]);

        $currency->update($validated);

        return response()->json($currency, 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $currency = Currency::findOrFail($id);
        $currency->delete();

        return response()->json(null, 204);
    }
}
