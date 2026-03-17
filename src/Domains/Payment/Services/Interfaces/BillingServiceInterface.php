<?php

namespace Payment\Services\Interfaces;

use Payment\Dtos\BillingUpdateDto;
use Payment\Models\Billing;

interface BillingServiceInterface
{
    /**
     * Get the billing for the current company.
     *
     * @return Billing
     */
    public function getBilling(): Billing;

    /**
     * Update the billing for the current company.
     *
     * @param BillingUpdateDto $dto
     * @return Billing
     */
    public function updateBilling(BillingUpdateDto $dto): Billing;
}
