<?php

namespace App\Http\Controllers\Proformas;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Payment\Services\Interfaces\ProformaServiceInterface;

readonly class GetProformasController
{
    /**
     * @param \Payment\Services\Interfaces\ProformaServiceInterface $proformaService
     */
    public function __construct(private ProformaServiceInterface $proformaService)
    {
    }

    /**
     * Retrieve all proformas for the authenticated user's company (paginated).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json($this->proformaService->getAllProformas($request->query()));
    }
}
