<?php

namespace Payment\Repositories;

use Payment\Models\Billing;

class BillingRepository implements BillingRepositoryInterface
{
    /**
     * Get the billing for the current company.
     *
     * @return Billing
     */
    public function findByCompany(): Billing
    {
        return Billing::firstOrFail();
    }

    /**
     * Update a billing and return the fresh model.
     *
     * @param Billing $billing
     * @param array $data
     * @return Billing
     */
    public function update(Billing $billing, array $data): Billing
    {
        $billing->update($data);
        return $billing->fresh();
    }
}
