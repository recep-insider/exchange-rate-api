<?php

namespace App\Http\Services;

use App\Traits\RedisKeys;
use App\Models\ExchangeRates;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class FetchCurrencies
{
    use RedisKeys;

    /**
     * @param RequestService $requestService
     */
    public function __construct(protected RequestService $requestService)
    {
    }

    /**
     * @param string $baseCurrency
     * @param array $targetCurrencies
     * @return void
     * @throws Exception
     */
    public function fetch(string $baseCurrency, array $targetCurrencies): void
    {
        $response = $this->requestService->get($baseCurrency);

        if (Arr::exists($response, 'error')) {
            return;
        }

        if (Arr::exists($response, 'conversion_rates')) {
            collect(Arr::get($response, 'conversion_rates', []))->filter(function ($value, $key) use ($targetCurrencies) {
                return in_array($key, $targetCurrencies);
            })->each(function($value, $key) use ($baseCurrency) {
                ExchangeRates::updateOrCreate(
                    ['based_currency' => $baseCurrency, 'target_currency' => $key],
                    ['rate' => $value]
                );

                Cache::put(
                    $this->getExchangeRateKey($baseCurrency, $key),
                    $value,
                    Carbon::SECONDS_PER_MINUTE * Carbon::MINUTES_PER_HOUR
                );
            });
        }
    }
}
