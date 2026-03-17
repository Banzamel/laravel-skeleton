<?php

namespace Payment\Dtos;

readonly class ServiceCreateDto
{
    /**
     * Service creation DTO constructor.
     *
     * @param string $name
     * @param int $limit
     * @param string|null $description
     * @param float $price
     */
    public function __construct(
        private string $name,
        private int $limit = 0,
        private ?string $description = null,
        private float $price = 0,
    ) {}

    /**
     * Get the service name.
     *
     * @return string
     */
    public function getName(): string { return $this->name; }

    /**
     * Get the service limit.
     *
     * @return int
     */
    public function getLimit(): int { return $this->limit; }

    /**
     * Get the service description.
     *
     * @return string|null
     */
    public function getDescription(): ?string { return $this->description; }

    /**
     * Get the service price.
     *
     * @return float
     */
    public function getPrice(): float { return $this->price; }

    /**
     * Convert DTO to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'limit' => $this->limit,
            'description' => $this->description,
            'price' => $this->price,
        ];
    }
}
