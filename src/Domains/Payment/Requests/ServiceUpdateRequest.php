<?php

namespace Payment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Payment\Dtos\ServiceUpdateDto;

final class ServiceUpdateRequest extends FormRequest
{
    /**
     * Check permissions for service update.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('services.update');
    }

    /**
     * Validation rules for service update.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'limit' => 'sometimes|integer|min:0',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
        ];
    }

    /**
     * Create a DTO from validated data.
     *
     * @return ServiceUpdateDto
     */
    public function getDto(): ServiceUpdateDto
    {
        return new ServiceUpdateDto(
            name: $this->input('name'),
            limit: $this->has('limit') ? (int) $this->input('limit') : null,
            description: $this->input('description'),
            price: $this->has('price') ? (float) $this->input('price') : null,
        );
    }
}
