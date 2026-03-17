<?php

namespace Tests\Unit\Dtos;

use Payment\Dtos\ProformaUpdateDto;
use PHPUnit\Framework\TestCase;

class ProformaUpdateDtoTest extends TestCase
{
    public function test_empty_dto_returns_empty_array(): void
    {
        $dto = new ProformaUpdateDto();

        $this->assertSame([], $dto->toArray());
        $this->assertNull($dto->getServices());
    }

    public function test_to_array_filters_null_values(): void
    {
        $dto = new ProformaUpdateDto(billingName: 'Updated');

        $this->assertSame(['billing_name' => 'Updated'], $dto->toArray());
    }

    public function test_to_array_excludes_services(): void
    {
        $services = [['service_id' => 1, 'quantity' => 3, 'discount' => 5]];
        $dto = new ProformaUpdateDto(services: $services);

        $this->assertSame([], $dto->toArray());
        $this->assertSame($services, $dto->getServices());
    }

    public function test_to_array_with_all_fields(): void
    {
        $dto = new ProformaUpdateDto(
            dueDate: '2026-06-01',
            billingName: 'New',
            address: 'Addr',
            city: 'City',
            country: 'US',
        );

        $array = $dto->toArray();

        $this->assertCount(5, $array);
        $this->assertSame('US', $array['country']);
    }
}
