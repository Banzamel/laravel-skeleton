<?php

namespace Tests\Unit\Dtos;

use Payment\Dtos\BillingUpdateDto;
use PHPUnit\Framework\TestCase;

class BillingUpdateDtoTest extends TestCase
{
    public function test_empty_dto_returns_empty_array(): void
    {
        $dto = new BillingUpdateDto();

        $this->assertSame([], $dto->toArray());
    }

    public function test_to_array_filters_null_values(): void
    {
        $dto = new BillingUpdateDto(taxId: '1234567890', bankName: 'PKO');

        $this->assertSame([
            'tax_id' => '1234567890',
            'bank_name' => 'PKO',
        ], $dto->toArray());
    }

    public function test_to_array_with_all_fields(): void
    {
        $dto = new BillingUpdateDto(
            taxId: '111',
            billingAddress: 'Street 1',
            billingCity: 'Warsaw',
            billingCountry: 'PL',
            bankName: 'PKO',
            bankAccount: 'PL12345678',
            proformaFormat: 'PRO/{YYYY}/{NR}',
            invoiceFormat: 'INV/{YYYY}/{NR}',
        );

        $array = $dto->toArray();

        $this->assertCount(8, $array);
        $this->assertSame('111', $array['tax_id']);
        $this->assertSame('Street 1', $array['billing_address']);
        $this->assertSame('Warsaw', $array['billing_city']);
        $this->assertSame('PL', $array['billing_country']);
        $this->assertSame('PKO', $array['bank_name']);
        $this->assertSame('PL12345678', $array['bank_account']);
        $this->assertSame('PRO/{YYYY}/{NR}', $array['proforma_format']);
        $this->assertSame('INV/{YYYY}/{NR}', $array['invoice_format']);
    }

    public function test_partial_update(): void
    {
        $dto = new BillingUpdateDto(billingCity: 'Krakow', billingCountry: 'PL');

        $array = $dto->toArray();

        $this->assertCount(2, $array);
        $this->assertArrayNotHasKey('tax_id', $array);
        $this->assertArrayNotHasKey('bank_name', $array);
    }
}
