<?php

namespace Tests\Unit\Dtos;

use Payment\Dtos\ServiceCreateDto;
use PHPUnit\Framework\TestCase;

class ServiceCreateDtoTest extends TestCase
{
    public function test_minimal_construction(): void
    {
        $dto = new ServiceCreateDto('Basic');

        $this->assertSame('Basic', $dto->getName());
        $this->assertSame(0, $dto->getLimit());
        $this->assertNull($dto->getDescription());
        $this->assertSame(0.0, $dto->getPrice());
    }

    public function test_full_construction(): void
    {
        $dto = new ServiceCreateDto('Premium', 20, 'Premium package', 299.99);

        $this->assertSame('Premium', $dto->getName());
        $this->assertSame(20, $dto->getLimit());
        $this->assertSame('Premium package', $dto->getDescription());
        $this->assertSame(299.99, $dto->getPrice());
    }

    public function test_to_array(): void
    {
        $dto = new ServiceCreateDto('Test', 10, 'Desc', 50.0);

        $this->assertSame([
            'name' => 'Test',
            'limit' => 10,
            'description' => 'Desc',
            'price' => 50.0,
        ], $dto->toArray());
    }
}
