<?php

namespace App\Http\Controllers\Proformas;

use Illuminate\Http\JsonResponse;
use Payment\Services\Interfaces\ProformaServiceInterface;

readonly class RestoreProformaController
{
    /**
     * @param \Payment\Services\Interfaces\ProformaServiceInterface $proformaService
     */
    public function __construct(private ProformaServiceInterface $proformaService)
    {
    }

    /**
     * Restore a soft-deleted proforma.
     *
     * @param int $proforma
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $proforma): JsonResponse
    {
        return response()->json($this->proformaService->restoreProforma($proforma));
    }
}
