<?php

namespace Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Shared\Traits\BelongsToCompany;
use Shared\Traits\Loggable;

class Billing extends Model
{
    use Loggable, BelongsToCompany;

    protected $table = 'mgr_billings';

    protected $fillable = [
        'company_id',
        'tax_id',
        'billing_address',
        'billing_city',
        'billing_country',
        'bank_name',
        'bank_account',
        'proforma_format',
        'invoice_format',
    ];
}
