<?php

namespace Payment\Services;

use Payment\Dtos\BillingUpdateDto;
use Payment\Events\BillingUpdatedEvent;
use Payment\Models\Billing;
use Payment\Repositories\BillingRepositoryInterface;
use Payment\Services\Interfaces\BillingServiceInterface;

readonly class BillingService implements BillingServiceInterface
{
    public function __construct(
        private BillingRepositoryInterface $billingRepository,
    ) {}

    /**
     * Get the billing for the current company.
     *
     * @return Billing
     */
    public function getBilling(): Billing
    {
        return $this->billingRepository->findByCompany();
    }

    /**
     * Update the billing for the current company.
     *
     * @param BillingUpdateDto $dto
     * @return Billing
     */
    public function updateBilling(BillingUpdateDto $dto): Billing
    {
        $billing = $this->billingRepository->findByCompany();
        $billing = $this->billingRepository->update($billing, $dto->toArray());

        event(new BillingUpdatedEvent($billing, auth()->user()));

        return $billing;
    }
}
