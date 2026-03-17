<?php

namespace Payment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Payment\Dtos\ServiceCreateDto;

final class ServiceCreateRequest extends FormRequest
{
    /**
     * Check permissions for service creation.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('services.create');
    }

    /**
     * Validation rules for service creation.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'limit' => 'integer|min:0',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
        ];
    }

    /**
     * Create a DTO from validated data.
     *
     * @return ServiceCreateDto
     */
    public function getDto(): ServiceCreateDto
    {
        return new ServiceCreateDto(
            name: $this->input('name'),
            limit: (int) $this->input('limit', 0),
            description: $this->input('description'),
            price: (float) $this->input('price', 0),
        );
    }
}
