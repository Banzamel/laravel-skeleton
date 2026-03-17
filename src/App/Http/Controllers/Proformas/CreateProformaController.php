<?php

namespace App\Http\Controllers\Proformas;

use Illuminate\Http\JsonResponse;
use Payment\Requests\ProformaCreateRequest;
use Payment\Services\Interfaces\ProformaServiceInterface;

readonly class CreateProformaController
{
    /**
     * @param \Payment\Services\Interfaces\ProformaServiceInterface $proformaService
     */
    public function __construct(private ProformaServiceInterface $proformaService)
    {
    }

    /**
     * Store a newly created proforma with services.
     *
     * @param \Payment\Requests\ProformaCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ProformaCreateRequest $request): JsonResponse
    {
        return response()->json($this->proformaService->createProforma($request->getDto()), 201);
    }
}
