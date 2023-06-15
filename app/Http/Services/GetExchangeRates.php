<?php

namespace App\Http\Services;

use App\Traits\RedisKeys;
use App\Models\ExchangeRates;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GetExchangeRates
{
    use RedisKeys;

    /**
     * @param Request $request
     * @return array
     */
    public function get(Request $request): array
    {
        $baseCurrency = $request->get('base_currency');
        $targetCurrencies = str_replace(['[', ']', ' '], '', $request->get('target_currencies'));
        $targetCurrencies = collect(array_unique(explode(',', $targetCurrencies)));
        $exchanges =  $targetCurrencies->map(function ($currency) use ($baseCurrency) {
            $rate = $this->getRate($baseCurrency, $currency);

            if (!is_null($rate)) {
                return [
                    'base' => $baseCurrency,
                    'target' => $currency,
                    'rate' => (float) $rate,
                ];
            }
        });

        return $exchanges->filter(function ($item) {
            return !is_null($item);
        })->toArray();
    }

    /**
     * @param string $baseCurrency
     * @param string $currency
     * @return mixed
     */
    protected function getRate(string $baseCurrency, string $currency): mixed
    {
        return Cache::remember(
            $this->getExchangeRateKey($baseCurrency, $currency),
            Carbon::SECONDS_PER_MINUTE * Carbon::MINUTES_PER_HOUR,
            function () use ($baseCurrency, $currency) {
                $exchangeRate = ExchangeRates::select('rate')
                    ->where([
                        'based_currency' => $baseCurrency,
                        'target_currency' => $currency,
                    ])
                    ->first();

                if (!is_null($exchangeRate)) {
                    return $exchangeRate->rate;
                }
            });
    }
}
