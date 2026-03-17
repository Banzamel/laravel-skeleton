<?php

namespace Tests\Unit\Dtos;

use Payment\Dtos\ProformaCreateDto;
use PHPUnit\Framework\TestCase;

class ProformaCreateDtoTest extends TestCase
{
    public function test_minimal_construction(): void
    {
        $dto = new ProformaCreateDto(
            dueDate: '2026-04-01',
            billingName: 'Test Client',
        );

        $this->assertSame('2026-04-01', $dto->getDueDate());
        $this->assertSame('Test Client', $dto->getBillingName());
        $this->assertNull($dto->getAddress());
        $this->assertNull($dto->getCity());
        $this->assertSame('PL', $dto->getCountry());
        $this->assertSame([], $dto->getServices());
    }

    public function test_full_construction(): void
    {
        $services = [
            ['service_id' => 1, 'quantity' => 2, 'discount' => 10.0],
        ];

        $dto = new ProformaCreateDto(
            dueDate: '2026-05-01',
            billingName: 'Company X',
            address: 'Main St 1',
            city: 'Warsaw',
            country: 'PL',
            services: $services,
        );

        $this->assertSame('Main St 1', $dto->getAddress());
        $this->assertSame('Warsaw', $dto->getCity());
        $this->assertSame($services, $dto->getServices());
    }

    public function test_to_array_excludes_services(): void
    {
        $dto = new ProformaCreateDto(
            dueDate: '2026-04-01',
            billingName: 'Test',
            services: [['service_id' => 1, 'quantity' => 1, 'discount' => 0]],
        );

        $array = $dto->toArray();

        $this->assertArrayNotHasKey('services', $array);
        $this->assertArrayHasKey('due_date', $array);
        $this->assertArrayHasKey('billing_name', $array);
    }

    public function test_default_country_is_pl(): void
    {
        $dto = new ProformaCreateDto('2026-04-01', 'Test');

        $this->assertSame('PL', $dto->getCountry());
        $this->assertSame('PL', $dto->toArray()['country']);
    }
}
