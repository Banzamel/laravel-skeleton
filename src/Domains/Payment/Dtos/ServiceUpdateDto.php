<?php

namespace Payment\Dtos;

readonly class ServiceUpdateDto
{
    /**
     * Service update DTO constructor.
     *
     * @param string|null $name
     * @param int|null $limit
     * @param string|null $description
     * @param float|null $price
     */
    public function __construct(
        private ?string $name = null,
        private ?int $limit = null,
        private ?string $description = null,
        private ?float $price = null,
    ) {}

    /**
     * Convert DTO to array, excluding null values.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'limit' => $this->limit,
            'description' => $this->description,
            'price' => $this->price,
        ], fn($value) => $value !== null);
    }
}
