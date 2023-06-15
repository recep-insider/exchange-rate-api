<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRates extends Model
{
    use HasFactory;

    public $table = 'exchange_rates';

    protected $fillable = ['id', 'based_currency', 'target_currency', 'rate'];
}
