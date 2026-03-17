<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\JsonResponse;
use Payment\Services\Interfaces\ServiceManagementServiceInterface;

readonly class GetServicesController
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
     * Retrieve all available services.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return response()->json($this->serviceManagement->getAllServices());
    }
}
