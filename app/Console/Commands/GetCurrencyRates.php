<?php

namespace App\Console\Commands;

use App\Http\Services\FetchCurrencies;
use Illuminate\Console\Command;

class GetCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:currency:rates {base_currency} {target_currencies*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(FetchCurrencies $fetchCurrencies)
    {
        $fetchCurrencies->fetch($this->argument('base_currency'), $this->argument('target_currencies'));
    }
}
