<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\JsonResponse;
use Payment\Services\Interfaces\ServiceManagementServiceInterface;

readonly class GetServiceController
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
     * Retrieve the specified service by ID.
     *
     * @param int $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $service): JsonResponse
    {
        return response()->json($this->serviceManagement->getServiceById($service));
    }
}
