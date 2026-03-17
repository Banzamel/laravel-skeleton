<?php

namespace Payment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Payment\Dtos\InvoiceUpdateDto;

final class InvoiceUpdateRequest extends FormRequest
{
    /**
     * Check permissions for invoice update.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('payments.update');
    }

    /**
     * Validation rules for invoice update (status only).
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:pending,paid,cancelled,overdue',
        ];
    }

    /**
     * Create a DTO from validated data.
     *
     * @return InvoiceUpdateDto
     */
    public function getDto(): InvoiceUpdateDto
    {
        return new InvoiceUpdateDto(
            status: $this->input('status'),
        );
    }
}
