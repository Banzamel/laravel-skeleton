<?php

namespace Shared\Helpers;

use Illuminate\Support\Collection;

class BlueprintHelper
{
    /**
     * Add created_by and updated_by columns to the migration.
     *
     * @param \Illuminate\Database\Schema\Blueprint $blueprint
     * @return Collection
     */
    static function usersStamps(\Illuminate\Database\Schema\Blueprint $blueprint): Collection
    {
        return new Collection([
            $blueprint->unsignedBigInteger('created_by')->nullable(),
            $blueprint->unsignedBigInteger('updated_by')->nullable(),
        ]);
    }
}
