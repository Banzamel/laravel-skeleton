<?php

namespace Payment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Payment\Dtos\BillingUpdateDto;

final class BillingUpdateRequest extends FormRequest
{
    /**
     * Check permissions for billing update.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('settings.manage');
    }

    /**
     * Validation rules for billing update.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'tax_id' => 'sometimes|nullable|string|max:255',
            'billing_address' => 'sometimes|nullable|string|max:255',
            'billing_city' => 'sometimes|nullable|string|max:255',
            'billing_country' => 'sometimes|string|max:2',
            'bank_name' => 'sometimes|nullable|string|max:255',
            'bank_account' => 'sometimes|nullable|string|max:255',
            'proforma_format' => 'sometimes|string|max:255',
            'invoice_format' => 'sometimes|string|max:255',
        ];
    }

    /**
     * Create a DTO from validated data.
     *
     * @return BillingUpdateDto
     */
    public function getDto(): BillingUpdateDto
    {
        return new BillingUpdateDto(
            taxId: $this->input('tax_id'),
            billingAddress: $this->input('billing_address'),
            billingCity: $this->input('billing_city'),
            billingCountry: $this->input('billing_country'),
            bankName: $this->input('bank_name'),
            bankAccount: $this->input('bank_account'),
            proformaFormat: $this->input('proforma_format'),
            invoiceFormat: $this->input('invoice_format'),
        );
    }
}
