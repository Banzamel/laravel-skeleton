<?php

namespace Shared\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    /**
     * Guard against recursive auth()->user() calls.
     */
    private static bool $resolving = false;

    /**
     * Apply the company scope to all queries.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (self::$resolving) {
            return;
        }

        self::$resolving = true;

        try {
            $user = auth()->user();

            if ($user && isset($user->company_id)) {
                $builder->where($model->getTable() . '.company_id', $user->company_id);
            }
        } finally {
            self::$resolving = false;
        }
    }
}
