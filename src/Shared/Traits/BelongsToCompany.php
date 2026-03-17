<?php

namespace Shared\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Administration\Models\Company;
use Shared\Scopes\CompanyScope;

trait BelongsToCompany
{
    /**
     * Boot the trait: register global scope and auto-set company_id on creating.
     *
     * @return void
     */
    public static function bootBelongsToCompany(): void
    {
        static::addGlobalScope(new CompanyScope());

        static::creating(function ($model) {
            if (empty($model->company_id)) {
                $user = auth()->user();
                if ($user && isset($user->company_id)) {
                    $model->company_id = $user->company_id;
                }
            }
        });
    }

    /**
     * Relationship to the company.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
