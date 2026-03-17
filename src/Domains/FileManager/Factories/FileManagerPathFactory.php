<?php

namespace FileManager\Factories;

use FileManager\Models\FileManagerPath;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FileManagerPathFactory extends Factory
{
    protected $model = FileManagerPath::class;

    /**
     * Define the default attributes for the file path model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hash' => $this->faker->unique()->word(),
            'size' => $this->faker->numberBetween(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => 0,
            'updated_by' => 0,
        ];
    }
}
