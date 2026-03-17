<?php

namespace Administration\Observers;

use Payment\Models\Billing;
use Administration\Models\Company;

class CompanyObserver
{
    /**
     * Auto-create billing record when a company is created.
     *
     * @param Company $company
     * @return void
     */
    public function created(Company $company): void
    {
        Billing::withoutGlobalScopes()->create([
            'company_id' => $company->id,
            'billing_country' => $company->country ?? 'PL',
        ]);
    }
}
