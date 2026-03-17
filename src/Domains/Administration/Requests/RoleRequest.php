<?php

namespace Administration\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Administration\Dtos\RoleDto;

final class RoleRequest extends FormRequest
{
    /**
     * Check permissions for role management.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('permissions.manage');
    }

    /**
     * Validation rules for a role.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string|exists:auth_permissions,name',
        ];
    }

    /**
     * Create a DTO from validated data.
     *
     * @return RoleDto
     */
    public function getDto(): RoleDto
    {
        return new RoleDto(
            name: $this->input('name'),
            permissions: $this->input('permissions'),
        );
    }
}
