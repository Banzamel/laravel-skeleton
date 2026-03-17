<?php

namespace FileManager\Requests;

use FileManager\Enums\EntityTypeEnum;
use FileManager\Enums\StoragesEnum;
use Illuminate\Foundation\Http\FormRequest;

class PathRequest extends FormRequest
{
    /**
     * Get the validation rules for file path.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'path' => ['string'],
            'storage' => ['string', 'in:' . join(',', array_column(StoragesEnum::cases(), 'value'))],
            'type' => ['string', 'in:' . join(',', array_column(EntityTypeEnum::cases(), 'value'))],
            'name' => ['string'],
            'file' => ['file'],
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
