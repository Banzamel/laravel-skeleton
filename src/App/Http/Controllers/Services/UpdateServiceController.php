<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\JsonResponse;
use Payment\Requests\ServiceUpdateRequest;
use Payment\Services\Interfaces\ServiceManagementServiceInterface;

readonly class UpdateServiceController
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
     * Update the specified service.
     *
     * @param \Payment\Requests\ServiceUpdateRequest $request
     * @param int $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ServiceUpdateRequest $request, int $service): JsonResponse
    {
        return response()->json($this->serviceManagement->updateService($service, $request->getDto()));
    }
}
