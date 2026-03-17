<?php

namespace Payment\Dtos;

readonly class ProformaUpdateDto
{
    /**
     * Proforma update DTO constructor.
     *
     * @param string|null $dueDate
     * @param string|null $billingName
     * @param string|null $address
     * @param string|null $city
     * @param string|null $country
     * @param array<int, array{service_id: int, quantity: int, discount: float}>|null $services
     */
    public function __construct(
        private ?string $dueDate = null,
        private ?string $billingName = null,
        private ?string $address = null,
        private ?string $city = null,
        private ?string $country = null,
        private ?array $services = null,
    ) {}

    public function getServices(): ?array { return $this->services; }

    /**
     * Convert DTO to array, excluding null values (without services).
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'due_date' => $this->dueDate,
            'billing_name' => $this->billingName,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
        ], fn($value) => $value !== null);
    }
}
