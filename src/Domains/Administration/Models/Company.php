<?php

namespace Administration\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Administration\Observers\CompanyObserver;
use Shared\Scopes\HasActiveScope;

#[ObservedBy(CompanyObserver::class)]
class Company extends Model
{
    use SoftDeletes, HasActiveScope;

    protected $table = 'sec_companies';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'country',
        'is_active',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'is_active' => 'boolean',
        'expired_at' => 'datetime'
    ];

    /**
     * Check if the company subscription is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expired_at->isPast();
    }

    /**
     * Get the users for the company.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the invoices for the company.
     *
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(\Payment\Models\Invoice::class);
    }

    /**
     * Get the billing settings for the company.
     *
     * @return HasOne
     */
    public function billing(): HasOne
    {
        return $this->hasOne(\Payment\Models\Billing::class);
    }
}
