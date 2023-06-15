<?php

namespace Tests\Unit;

use App\Http\Services\FetchCurrencies;
use App\Http\Services\RequestService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Mockery;

class FetchCurrenciesTest extends TestCase
{
    protected Mockery\LegacyMockInterface|RequestService|Mockery\MockInterface $requestService;
    protected FetchCurrencies $fetchCurrencies;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->requestService = Mockery::mock(RequestService::class);
        $this->fetchCurrencies = new FetchCurrencies($this->requestService);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testFetchCurrencies()
    {
        // Arrange
        $baseCurrency = 'USD';
        $targetCurrencies = ['EUR', 'GBP'];
        $response = [
            'conversion_rates' => [
                'EUR' => 0.84,
                'GBP' => 0.75,
            ],
        ];

        // Act
        $this->requestService->shouldReceive('get')->with($baseCurrency)->andReturn($response);
        $this->fetchCurrencies->fetch($baseCurrency, $targetCurrencies);

        // Assert
        foreach ($targetCurrencies as $currency) {
            $this->assertTrue(Cache::has("based:{$baseCurrency}:target:{$currency}"));
            $this->assertEquals($response['conversion_rates'][$currency], Cache::get("based:{$baseCurrency}:target:{$currency}"));
            $this->assertDatabaseHas('exchange_rates', [
                'based_currency' => $baseCurrency,
                'target_currency' => $currency,
                'rate' => $response['conversion_rates'][$currency],
            ]);
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFetchCurrenciesReturnNothingWhenHasError()
    {
        $baseCurrency = 'USD';
        $targetCurrencies = ['EUR', 'GBP'];
        $response = ['error' => true];

        $this->requestService->shouldReceive('get')->with($baseCurrency)->andReturn($response);
        $this->fetchCurrencies->fetch($baseCurrency, $targetCurrencies);

        $this->assertNull(null);
    }
}
