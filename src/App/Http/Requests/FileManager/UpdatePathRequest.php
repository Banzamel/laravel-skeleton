<?php

namespace App\Http\Requests\FileManager;

use FileManager\Requests\PathRequest;

class UpdatePathRequest extends PathRequest
{
    /**
     * Get the validation rules for updating a file path.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge_recursive(parent::rules(), []);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('materials.update');
    }
}
