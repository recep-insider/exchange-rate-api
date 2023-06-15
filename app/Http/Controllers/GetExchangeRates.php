<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Services\GetExchangeRates as ExchangeRateService;

class GetExchangeRates extends Controller
{
    public function __construct(protected ExchangeRateService $exchangeRateService)
    {
    }

    public function get(Request $request): JsonResponse
    {
        return response()->json($this->exchangeRateService->get($request));
    }
}
