<?php

namespace FileManager\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MetaRequest extends FormRequest
{
    /**
     * Get the validation rules for file meta.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'mime_type' => ['string', 'max:255'],
            'extension' => ['string', 'max:31'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
