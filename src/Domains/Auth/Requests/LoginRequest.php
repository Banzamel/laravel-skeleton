<?php

namespace Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|exists:sec_users,email',
            'password' => 'required|string',
        ];
    }

    /**
     * Create a LoginDto from the validated request data.
     *
     * @return \Auth\Dtos\LoginDto
     */
    public function getDto(): \Auth\Dtos\LoginDto
    {
        return new \Auth\Dtos\LoginDto(
            $this->input('email'),
            $this->input('password')
        );
    }
}
