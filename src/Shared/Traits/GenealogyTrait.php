<?php

namespace Shared\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait GenealogyTrait
{
    /**
     * Register model events for automatically setting created_by and updated_by.
     *
     * @return void
     */
    protected static function bootGenealogy(): void
    {
        static::creating(function ($model) {
            $model->created_by = auth()->id();
            $model->updated_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }
}
