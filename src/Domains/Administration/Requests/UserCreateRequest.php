<?php

namespace Administration\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Administration\Dtos\UserCreateDto;

final class UserCreateRequest extends FormRequest
{
    /**
     * Check permissions for user creation.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('users.create');
    }

    /**
     * Validation rules for user creation.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:sec_users,email',
            'password' => 'required|string|min:8',
            'role_name' => 'required|string|exists:auth_roles,name',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Create a DTO from validated data.
     *
     * @return UserCreateDto
     */
    public function getDto(): UserCreateDto
    {
        return new UserCreateDto(
            name: $this->input('name'),
            email: $this->input('email'),
            password: $this->input('password'),
            roleName: $this->input('role_name'),
            isActive: $this->boolean('is_active', true),
        );
    }
}
