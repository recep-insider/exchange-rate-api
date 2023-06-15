<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;

/**
 * @coversDefaultClass \App\Console\Commands\GetCurrencyRates
 */
class GetCurrencyRatesTest extends TestCase
{
    /**
     * @test
     * @covers ::handle
     */
    public function it_should_handle(): void
    {
        $this->artisan('app:currency:rates EUR TRY')->assertExitCode(0);
    }
}
