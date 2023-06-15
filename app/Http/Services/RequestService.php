<?php

namespace App\Http\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class RequestService
{
    /**
     * @param string $baseCurrency
     * @return array
     */
    public function get(string $baseCurrency): array
    {
        try {
            $response = Http::get(
                env('EXCHANGE_RATE_API_HOST') . '/' . env('EXCHANGE_RATE_API_KEY') . '/latest/' . $baseCurrency
            );

            return $response->json();
        } catch (Exception $exception) {
            return [
                'error' => true,
                'message' => $exception->getMessage()
            ];
        }
    }
}
