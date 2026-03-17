<?php

namespace Payment\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Payment\Observers\ProformaObserver;

#[ObservedBy(ProformaObserver::class)]
class Proforma extends Invoice
{
    /**
     * Replace the parent 'invoice' scope with a 'proforma' scope.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('proforma', function (Builder $builder) {
            $builder->where('is_proforma', true);
        });
    }
}
