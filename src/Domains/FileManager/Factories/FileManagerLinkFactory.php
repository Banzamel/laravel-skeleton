<?php

namespace FileManager\Factories;

use FileManager\Models\FileManagerLink;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FileManagerLinkFactory extends Factory
{
    protected $model = FileManagerLink::class;

    /**
     * Define the default attributes for the file link model.
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
