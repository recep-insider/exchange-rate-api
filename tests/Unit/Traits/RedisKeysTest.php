<?php

namespace Tests\Domain\Shopify\Unit\Traits;

use App\Traits\RedisKeys;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Traits\RedisKeys
 */
class RedisKeysTest extends TestCase
{
    /**
     * @param array $ignoreMethods
     * @throws Exception
     * @return MockObject
     */
    protected function getIsolatedGeneratorTrait(array $ignoreMethods = []): MockObject
    {
        return $this->getIsolatedTraitMock(RedisKeys::class, $ignoreMethods);
    }

    /**
     * @test
     * @covers ::getPartnerUcdTtlKey
     * @throws Exception
     */
    function it_should_return_partner_ucd_ttl_key()
    {
        /** @var MockObject|RedisKeys $trait */
        $trait = $this->getIsolatedGeneratorTrait(['getExchangeRateKey']);

        $this->assertEquals('based:EUR:target:TRY', $trait->getExchangeRateKey('EUR', 'TRY'));
    }
}
