<?php

namespace Tests\Unit\Dtos;

use Payment\Dtos\ServiceUpdateDto;
use PHPUnit\Framework\TestCase;

class ServiceUpdateDtoTest extends TestCase
{
    public function test_empty_dto_returns_empty_array(): void
    {
        $dto = new ServiceUpdateDto();

        $this->assertSame([], $dto->toArray());
    }

    public function test_to_array_filters_null_values(): void
    {
        $dto = new ServiceUpdateDto(name: 'Updated', price: 150.0);

        $this->assertSame([
            'name' => 'Updated',
            'price' => 150.0,
        ], $dto->toArray());
    }

    public function test_to_array_with_all_fields(): void
    {
        $dto = new ServiceUpdateDto('Updated', 15, 'New desc', 99.99);

        $this->assertSame([
            'name' => 'Updated',
            'limit' => 15,
            'description' => 'New desc',
            'price' => 99.99,
        ], $dto->toArray());
    }
}
