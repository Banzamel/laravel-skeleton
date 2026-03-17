<?php

namespace Administration\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Administration\Dtos\UserUpdateDto;

final class UserUpdateRequest extends FormRequest
{
    /**
     * Check permissions for user update.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('users.update');
    }

    /**
     * Validation rules for user update.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:sec_users,email,' . $userId,
            'password' => 'sometimes|string|min:8',
            'role_name' => 'sometimes|string|exists:roles,name',
            'is_active' => 'sometimes|boolean',
        ];
    }

    /**
     * Create a DTO from validated data.
     *
     * @return UserUpdateDto
     */
    public function getDto(): UserUpdateDto
    {
        return new UserUpdateDto(
            name: $this->input('name'),
            email: $this->input('email'),
            password: $this->input('password'),
            roleName: $this->input('role_name'),
            isActive: $this->has('is_active') ? $this->boolean('is_active') : null,
        );
    }
}
