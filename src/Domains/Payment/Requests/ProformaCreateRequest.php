<?php

namespace Payment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Payment\Dtos\ProformaCreateDto;

final class ProformaCreateRequest extends FormRequest
{
    /**
     * Check permissions for proforma creation.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('payments.create');
    }

    /**
     * Validation rules for proforma creation.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'due_date' => 'required|date|after_or_equal:today',
            'billing_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'string|max:2',
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|integer|exists:mgr_services,id',
            'services.*.quantity' => 'required|integer|min:1',
            'services.*.discount' => 'numeric|min:0',
        ];
    }

    /**
     * Create a DTO from validated data.
     *
     * @return ProformaCreateDto
     */
    public function getDto(): ProformaCreateDto
    {
        return new ProformaCreateDto(
            dueDate: $this->input('due_date'),
            billingName: $this->input('billing_name'),
            address: $this->input('address'),
            city: $this->input('city'),
            country: $this->input('country', 'PL'),
            services: $this->input('services', []),
        );
    }
}
