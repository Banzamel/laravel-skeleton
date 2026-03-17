<?php

namespace Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class RefreshLoginRequest extends FormRequest
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
            'refresh_token' => 'required|string',
        ];
    }

    /**
     * Create a RefreshLoginDto from the validated request data.
     *
     * @return \Auth\Dtos\RefreshLoginDto
     */
    public function getDto(): \Auth\Dtos\RefreshLoginDto
    {
        return new \Auth\Dtos\RefreshLoginDto(
            $this->input('refresh_token')
        );
    }
}
