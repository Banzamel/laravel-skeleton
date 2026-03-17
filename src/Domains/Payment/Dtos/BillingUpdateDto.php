<?php

namespace Payment\Dtos;

readonly class BillingUpdateDto
{
    /**
     * Billing update DTO constructor.
     *
     * @param string|null $taxId
     * @param string|null $billingAddress
     * @param string|null $billingCity
     * @param string|null $billingCountry
     * @param string|null $bankName
     * @param string|null $bankAccount
     * @param string|null $proformaFormat
     * @param string|null $invoiceFormat
     */
    public function __construct(
        private ?string $taxId = null,
        private ?string $billingAddress = null,
        private ?string $billingCity = null,
        private ?string $billingCountry = null,
        private ?string $bankName = null,
        private ?string $bankAccount = null,
        private ?string $proformaFormat = null,
        private ?string $invoiceFormat = null,
    ) {}

    /**
     * Convert DTO to array, excluding null values.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'tax_id' => $this->taxId,
            'billing_address' => $this->billingAddress,
            'billing_city' => $this->billingCity,
            'billing_country' => $this->billingCountry,
            'bank_name' => $this->bankName,
            'bank_account' => $this->bankAccount,
            'proforma_format' => $this->proformaFormat,
            'invoice_format' => $this->invoiceFormat,
        ], fn($value) => $value !== null);
    }
}
