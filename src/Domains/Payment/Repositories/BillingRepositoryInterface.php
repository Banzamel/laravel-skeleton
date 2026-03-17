<?php

namespace Payment\Repositories;

use Payment\Models\Billing;

interface BillingRepositoryInterface
{
    public function findByCompany(): Billing;
    public function update(Billing $billing, array $data): Billing;
}
