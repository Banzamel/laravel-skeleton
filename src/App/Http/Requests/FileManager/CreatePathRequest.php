<?php

namespace App\Http\Requests\FileManager;

use FileManager\Enums\EntityTypeEnum;
use FileManager\Requests\PathRequest;

class CreatePathRequest extends PathRequest
{
    /**
     * Get the validation rules for creating a file path.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge_recursive(parent::rules(), [
            'path' => ['required'],
            'type' => ['required'],
            'name' => ['required_if:type,' . EntityTypeEnum::dir->value],
            'file' => ['required_if:type,' . EntityTypeEnum::file->value],
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('materials.create');
    }
}
