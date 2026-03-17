<?php

namespace App\Http\Controllers\Proformas;

use Illuminate\Http\JsonResponse;
use Payment\Services\Interfaces\ProformaServiceInterface;

readonly class DeleteProformaController
{
    /**
     * @param \Payment\Services\Interfaces\ProformaServiceInterface $proformaService
     */
    public function __construct(private ProformaServiceInterface $proformaService)
    {
    }

    /**
     * Delete the specified proforma (soft delete).
     *
     * @param int $proforma
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $proforma): JsonResponse
    {
        $this->proformaService->deleteProforma($proforma);

        return response()->json(null, 204);
    }
}
