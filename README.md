## Build Project
You should have Docker for build this project. If you have docker application you can follow these steps:
- cd exchange-rate-api
- docker-compose up -d
- docker exec -it app bash
- php artisan migrate

## Sample Command for Collect Exchanges
php artisan app:currency:rates EUR AED AFN ALL AMD ANG AOA ARS AUD AWG AZN BAM BBD BDT BGN BHD BIF BMD BND BOB BRL BSD BTN BWP BYN BZD CAD CDF CHF CLP CNY COP CRC CUP CVE CZK DJ
F DKK DOP DZD EGP ERN ETB FJD FKP FOK GBP GEL GGP GHS GIP GMD GNF GTQ GYD HKD HNL HRK HTG HUF IDR ILS IMP INR IQD IRR ISK JEP JMD JOD JPY KES KGS KHR KID KMF KRW KWD KYD KZT LAK LBP LKR LRD LSL LYD MAD MDL MGA MKD MMK MNT MOP MRU MUR MVR MWK MXN MYR MZN NAD NGN NIO NOK NPR NZD OMR PAB PEN PGK PHP PKR PLN PYG QAR RON RSD RUB RWF SAR SBD SCR SDG SEK SGD SHP SLE SOS SRD SSP STN SYP SZL THB TJS TMT TND TOP TRY TTD TVD TWD TZS UAH UGX USD UYU UZS VES VND VUV WST XAF XCD XDR XOF XPF YER ZAR ZMW ZWL

First parameter is based currency and other ones are target currencies. 
I used https://www.exchangerate-api.com/ you can find all currencies in this command.

## Sample Request for Getting Exchange Rates
You can use this request: http://localhost/api/exchange-rates?base_currency=EUR&target_currencies=[TRY,USD,GBP,AED]

## For running unit test you can follow these steps

- docker exec -it app bash
- ./vendor/bin/phpunit tests/

