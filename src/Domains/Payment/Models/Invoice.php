<?php

namespace Payment\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Payment\Observers\InvoiceObserver;
use Shared\Traits\BelongsToCompany;
use Shared\Traits\Loggable;

#[ObservedBy(InvoiceObserver::class)]
class Invoice extends Model
{
    use Loggable, SoftDeletes, BelongsToCompany;

    protected $table = 'mgr_invoices';

    protected $fillable = [
        'company_id',
        'is_proforma',
        'proforma_number',
        'invoice_number',
        'due_date',
        'total_amount',
        'currency',
        'status',
        'billing_name',
        'address',
        'city',
        'country',
    ];

    /**
     * Country to currency mapping.
     */
    public const array COUNTRY_CURRENCY_MAP = [
        'PL' => 'PLN',
        'US' => 'USD',
        'GB' => 'GBP',
        'CZ' => 'CZK',
        'SE' => 'SEK',
        'NO' => 'NOK',
        'DK' => 'DKK',
        'CH' => 'CHF',
        'JP' => 'JPY',
        'CA' => 'CAD',
        'AU' => 'AUD',
    ];

    /**
     * Default currency for EU countries.
     */
    public const string DEFAULT_CURRENCY = 'EUR';

    /**
     * Boot the model — add global scope to filter confirmed invoices only.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('invoice', function (Builder $builder) {
            $builder->where('is_proforma', false);
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'total_amount' => 'decimal:2',
            'is_proforma' => 'boolean',
        ];
    }

    /**
     * @return BelongsToMany
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'mgr_invoice_services', 'invoice_id', 'service_id')
            ->withPivot('quantity', 'discount', 'price')
            ->withTimestamps();
    }

    /**
     * Resolve currency code from a country code.
     *
     * @param string $countryCode
     * @return string
     */
    public static function currencyForCountry(string $countryCode): string
    {
        return self::COUNTRY_CURRENCY_MAP[strtoupper($countryCode)] ?? self::DEFAULT_CURRENCY;
    }

}
