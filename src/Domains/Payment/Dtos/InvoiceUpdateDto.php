<?php

namespace Payment\Dtos;

readonly class InvoiceUpdateDto
{
    /**
     * Invoice update DTO constructor (status change only for confirmed invoices).
     *
     * @param string $status
     */
    public function __construct(
        private string $status,
    ) {}

    public function getStatus(): string { return $this->status; }

    /**
     * Convert DTO to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
        ];
    }
}
