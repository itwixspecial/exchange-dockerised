<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Exchanges;
use App\Models\Completed;

class StatisticController extends Controller
{
    public function getSystemFees(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $systemFees = Exchanges::select('currency_from_id as currency', DB::raw('SUM(commission) as amount'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereIn('id', function($query) {
                $query->select('exchange_request_id')
                      ->from(with(new Completed)->getTable());
            })
            ->groupBy('currency_from_id')
            ->get()
            ->map(function ($fee) {
                $fee->currency = $this->getCurrencyCode($fee->currency);
                return $fee;
            });

        return response()->json($systemFees);
    }

    private function getCurrencyCode($currencyId)
    {
        // Припустимо, що у вас є модель Currency з полем code
        return \App\Models\Currency::find($currencyId)->symb;
    }
}
