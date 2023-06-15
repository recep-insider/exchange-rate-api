<?php

namespace App\Traits;

trait RedisKeys
{
    /**
     * @param string $based
     * @param string $target
     * @return string
     */
    public function getExchangeRateKey(string $based, string $target): string
    {
        return "based:{$based}:target:{$target}";
    }
}
