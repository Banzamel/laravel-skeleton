<?php

namespace App\Http\Controllers\Billings;

use Illuminate\Http\JsonResponse;
use Payment\Services\Interfaces\BillingServiceInterface;

readonly class GetBillingController
{
    /**
     * Initialize the controller with the billing service.
     *
     * @param BillingServiceInterface $billingService
     */
    public function __construct(private BillingServiceInterface $billingService)
    {
    }

    /**
     * Get the billing for the current company.
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return response()->json($this->billingService->getBilling());
    }
}
