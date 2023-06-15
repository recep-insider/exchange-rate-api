<?php

namespace Tests\Unit;

use App\Http\Services\GetExchangeRates;
use App\Models\ExchangeRates;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;

class GetExchangeRatesTest extends TestCase
{
    protected GetExchangeRates $exchangeReteService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->exchangeReteService = new GetExchangeRates();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testGetReturnEmpty()
    {
        $request = new Request(['base_currency' => 'EUR', 'target_currencies' => '[TRY, USD]']);

        $this->assertEmpty($this->exchangeReteService->get($request));
    }

    /**
     * @return void
     */
    public function testGetReturnExchanges()
    {
        $request = new Request(['base_currency' => 'EUR', 'target_currencies' => '[TRY, USD]']);
        ExchangeRates::factory()->create(['based_currency' => 'EUR', 'target_currency' => 'TRY', 'rate' => 1]);
        $expected = [
            'base' => 'EUR',
            'target' => 'TRY',
            'rate' => 1,
        ];

        $this->assertEquals([$expected], $this->exchangeReteService->get($request));
    }
}
