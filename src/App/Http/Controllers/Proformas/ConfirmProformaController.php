<?php

namespace App\Http\Controllers\Proformas;

use Illuminate\Http\JsonResponse;
use Payment\Services\Interfaces\ProformaServiceInterface;

readonly class ConfirmProformaController
{
    /**
     * @param \Payment\Services\Interfaces\ProformaServiceInterface $proformaService
     */
    public function __construct(private ProformaServiceInterface $proformaService)
    {
    }

    /**
     * Confirm a proforma — converts it to a final invoice.
     *
     * @param int $proforma
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $proforma): JsonResponse
    {
        return response()->json($this->proformaService->confirmProforma($proforma));
    }
}
