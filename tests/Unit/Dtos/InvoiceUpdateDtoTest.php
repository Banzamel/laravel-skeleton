<?php

namespace Tests\Unit\Dtos;

use Payment\Dtos\InvoiceUpdateDto;
use PHPUnit\Framework\TestCase;

class InvoiceUpdateDtoTest extends TestCase
{
    public function test_getter_returns_correct_value(): void
    {
        $dto = new InvoiceUpdateDto('paid');

        $this->assertSame('paid', $dto->getStatus());
    }

    public function test_to_array(): void
    {
        $dto = new InvoiceUpdateDto('cancelled');

        $this->assertSame(['status' => 'cancelled'], $dto->toArray());
    }
}
