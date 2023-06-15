<?php

namespace Tests\Feature;

use App\Models\ExchangeRates;
use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\GetExchangeRates
 */
class GetExchangeRatesTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * @covers ::__construct
     * @covers ::get
     */
    public function it_should_return_exchange_rates(): void
    {
        $exchange = ExchangeRates::factory()->create([
            'based_currency' => $this->faker->currencyCode,
            'target_currency' => $this->faker->currencyCode,
            'rate' => 25.43,
        ]);
        $expected = [
            'base' => $exchange->based_currency,
            'target' => $exchange->target_currency,
            'rate' => 1
        ];

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(
                function ($key, $ttl, Closure $closure) {
                    $closure();

                    return true;
                }
            );

        $response = $this->get("/api/exchange-rates?base_currency={$exchange->based_currency}&target_currencies=[{$exchange->target_currency}]");

        $response->assertStatus(200)->assertJson([$expected]);
    }
}
