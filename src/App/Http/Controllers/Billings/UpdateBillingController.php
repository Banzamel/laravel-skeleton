<?php

namespace App\Http\Controllers\Billings;

use Illuminate\Http\JsonResponse;
use Payment\Requests\BillingUpdateRequest;
use Payment\Services\Interfaces\BillingServiceInterface;

readonly class UpdateBillingController
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
     * Update the billing for the current company.
     *
     * @param BillingUpdateRequest $request
     * @return JsonResponse
     */
    public function __invoke(BillingUpdateRequest $request): JsonResponse
    {
        return response()->json($this->billingService->updateBilling($request->getDto()));
    }
}
