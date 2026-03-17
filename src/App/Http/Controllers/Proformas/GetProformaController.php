<?php

namespace App\Http\Controllers\Proformas;

use Illuminate\Http\JsonResponse;
use Payment\Services\Interfaces\ProformaServiceInterface;

readonly class GetProformaController
{
    /**
     * @param \Payment\Services\Interfaces\ProformaServiceInterface $proformaService
     */
    public function __construct(private ProformaServiceInterface $proformaService)
    {
    }

    /**
     * Retrieve a single proforma by ID.
     *
     * @param int $proforma
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $proforma): JsonResponse
    {
        return response()->json($this->proformaService->getProformaById($proforma));
    }
}
