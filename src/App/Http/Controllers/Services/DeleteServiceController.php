<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\JsonResponse;
use Payment\Services\Interfaces\ServiceManagementServiceInterface;

readonly class DeleteServiceController
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
     * Delete the specified service.
     *
     * @param int $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $service): JsonResponse
    {
        $this->serviceManagement->deleteService($service);

        return response()->json(null, 204);
    }
}
