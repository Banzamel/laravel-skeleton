<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\JsonResponse;
use Payment\Requests\ServiceCreateRequest;
use Payment\Services\Interfaces\ServiceManagementServiceInterface;

readonly class CreateServiceController
{
    /**
     * Initialize the controller with the service management service.
     *
     * @param \Payment\Services\Interfaces\ServiceManagementServiceInterface $serviceManagement
     */
    public function __construct(private ServiceManagementServiceInterface $serviceManagement)
    {
    }

    /**
     * Store a newly created service.
     *
     * @param \Payment\Requests\ServiceCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ServiceCreateRequest $request): JsonResponse
    {
        return response()->json($this->serviceManagement->createService($request->getDto()), 201);
    }
}
