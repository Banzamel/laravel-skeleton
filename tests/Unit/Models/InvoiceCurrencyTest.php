<?php

namespace Tests\Unit\Models;

use Payment\Models\Invoice;
use PHPUnit\Framework\TestCase;

class InvoiceCurrencyTest extends TestCase
{
    public function test_known_country_returns_correct_currency(): void
    {
        $this->assertSame('PLN', Invoice::currencyForCountry('PL'));
        $this->assertSame('USD', Invoice::currencyForCountry('US'));
        $this->assertSame('GBP', Invoice::currencyForCountry('GB'));
        $this->assertSame('CZK', Invoice::currencyForCountry('CZ'));
        $this->assertSame('SEK', Invoice::currencyForCountry('SE'));
        $this->assertSame('NOK', Invoice::currencyForCountry('NO'));
        $this->assertSame('DKK', Invoice::currencyForCountry('DK'));
        $this->assertSame('CHF', Invoice::currencyForCountry('CH'));
        $this->assertSame('JPY', Invoice::currencyForCountry('JP'));
        $this->assertSame('CAD', Invoice::currencyForCountry('CA'));
        $this->assertSame('AUD', Invoice::currencyForCountry('AU'));
    }

    public function test_unknown_country_returns_eur(): void
    {
        $this->assertSame('EUR', Invoice::currencyForCountry('DE'));
        $this->assertSame('EUR', Invoice::currencyForCountry('FR'));
        $this->assertSame('EUR', Invoice::currencyForCountry('IT'));
        $this->assertSame('EUR', Invoice::currencyForCountry('XX'));
    }

    public function test_lowercase_country_code_is_handled(): void
    {
        $this->assertSame('PLN', Invoice::currencyForCountry('pl'));
        $this->assertSame('USD', Invoice::currencyForCountry('us'));
        $this->assertSame('EUR', Invoice::currencyForCountry('de'));
    }

    public function test_default_currency_constant(): void
    {
        $this->assertSame('EUR', Invoice::DEFAULT_CURRENCY);
    }

    public function test_country_currency_map_has_eleven_entries(): void
    {
        $this->assertCount(11, Invoice::COUNTRY_CURRENCY_MAP);
    }
}
