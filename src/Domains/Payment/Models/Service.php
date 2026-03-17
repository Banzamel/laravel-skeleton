<?php

namespace Payment\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Payment\Observers\ServiceObserver;
use Shared\Scopes\HasActiveScope;
use Shared\Traits\BelongsToCompany;
use Shared\Traits\Loggable;

#[ObservedBy(ServiceObserver::class)]
class Service extends Model
{
    use Loggable, SoftDeletes, BelongsToCompany, HasActiveScope;

    protected $table = 'mgr_services';

    protected $fillable = [
        'company_id',
        'name',
        'limit',
        'description',
        'price',
        'is_active',
    ];

    /**
     * Define the model's attribute casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'limit' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
