<?php

namespace Payment\Dtos;

readonly class ProformaCreateDto
{
    /**
     * Proforma creation DTO constructor.
     *
     * @param string $dueDate
     * @param string $billingName
     * @param string|null $address
     * @param string|null $city
     * @param string $country
     * @param array<int, array{service_id: int, quantity: int, discount: float}> $services
     */
    public function __construct(
        private string $dueDate,
        private string $billingName,
        private ?string $address = null,
        private ?string $city = null,
        private string $country = 'PL',
        private array $services = [],
    ) {}

    public function getDueDate(): string { return $this->dueDate; }
    public function getBillingName(): string { return $this->billingName; }
    public function getAddress(): ?string { return $this->address; }
    public function getCity(): ?string { return $this->city; }
    public function getCountry(): string { return $this->country; }
    public function getServices(): array { return $this->services; }

    /**
     * Convert DTO to array (without services — handled separately).
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'due_date' => $this->dueDate,
            'billing_name' => $this->billingName,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
        ];
    }
}
