<?php

namespace Administration\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for user avatar upload validation.
 */
final class UserAvatarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('users.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'avatar' => 'required|image|max:2048',
        ];
    }
}
