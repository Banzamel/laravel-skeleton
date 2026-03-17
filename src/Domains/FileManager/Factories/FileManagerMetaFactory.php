<?php

namespace FileManager\Factories;

use FileManager\Models\FileManagerMeta;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FileManagerMetaFactory extends Factory
{
    protected $model = FileManagerMeta::class;

    /**
     * Define the default attributes for the file metadata model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
