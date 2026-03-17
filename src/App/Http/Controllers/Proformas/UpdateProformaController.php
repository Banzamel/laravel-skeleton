<?php

namespace App\Http\Controllers\Proformas;

use Illuminate\Http\JsonResponse;
use Payment\Requests\ProformaUpdateRequest;
use Payment\Services\Interfaces\ProformaServiceInterface;

readonly class UpdateProformaController
{
    /**
     * @param \Payment\Services\Interfaces\ProformaServiceInterface $proformaService
     */
    public function __construct(private ProformaServiceInterface $proformaService)
    {
    }

    /**
     * Update the specified proforma.
     *
     * @param \Payment\Requests\ProformaUpdateRequest $request
     * @param int $proforma
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ProformaUpdateRequest $request, int $proforma): JsonResponse
    {
        return response()->json($this->proformaService->updateProforma($proforma, $request->getDto()));
    }
}
