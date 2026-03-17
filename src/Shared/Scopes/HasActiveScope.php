<?php

namespace Shared\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait HasActiveScope
{
    /**
     * Scope to only active records.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where($this->getTable() . '.is_active', true);
    }

    /**
     * Scope to only inactive records.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where($this->getTable() . '.is_active', false);
    }
}
