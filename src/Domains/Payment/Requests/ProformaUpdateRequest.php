<?php

namespace Payment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Payment\Dtos\ProformaUpdateDto;

final class ProformaUpdateRequest extends FormRequest
{
    /**
     * Check permissions for proforma update.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('payments.update');
    }

    /**
     * Validation rules for proforma update.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'due_date' => 'sometimes|date',
            'billing_name' => 'sometimes|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'sometimes|string|max:2',
            'services' => 'sometimes|array|min:1',
            'services.*.service_id' => 'required_with:services|integer|exists:mgr_services,id',
            'services.*.quantity' => 'required_with:services|integer|min:1',
            'services.*.discount' => 'numeric|min:0',
        ];
    }

    /**
     * Create a DTO from validated data.
     *
     * @return ProformaUpdateDto
     */
    public function getDto(): ProformaUpdateDto
    {
        return new ProformaUpdateDto(
            dueDate: $this->input('due_date'),
            billingName: $this->input('billing_name'),
            address: $this->input('address'),
            city: $this->input('city'),
            country: $this->input('country'),
            services: $this->has('services') ? $this->input('services') : null,
        );
    }
}
