<?php

namespace Payment\Services;

use Illuminate\Database\Eloquent\Collection;
use Payment\Dtos\ServiceCreateDto;
use Payment\Dtos\ServiceUpdateDto;
use Payment\Events\ServiceCreatedEvent;
use Payment\Events\ServiceDeletedEvent;
use Payment\Events\ServiceUpdatedEvent;
use Payment\Models\Service;
use Payment\Repositories\ServiceRepositoryInterface;
use Payment\Services\Interfaces\ServiceManagementServiceInterface;

readonly class ServiceManagementService implements ServiceManagementServiceInterface
{
    public function __construct(
        private ServiceRepositoryInterface $serviceRepository,
    ) {}

    /**
     * Get all services for the company.
     *
     * @return Collection
     */
    public function getAllServices(): Collection
    {
        return $this->serviceRepository->findAll();
    }

    /**
     * Get a service by ID.
     *
     * @param int $serviceId
     * @return Service
     */
    public function getServiceById(int $serviceId): Service
    {
        return $this->serviceRepository->findOrFail($serviceId);
    }

    /**
     * Create a new service.
     *
     * @param ServiceCreateDto $dto
     * @return Service
     */
    public function createService(ServiceCreateDto $dto): Service
    {
        $service = $this->serviceRepository->create($dto);

        event(new ServiceCreatedEvent($service, auth()->user()));

        return $service;
    }

    /**
     * Update an existing service.
     *
     * @param int $serviceId
     * @param ServiceUpdateDto $dto
     * @return Service
     */
    public function updateService(int $serviceId, ServiceUpdateDto $dto): Service
    {
        $service = $this->serviceRepository->findOrFail($serviceId);
        $service = $this->serviceRepository->update($service, $dto->toArray());

        event(new ServiceUpdatedEvent($service, auth()->user()));

        return $service;
    }

    /**
     * Delete a service (soft delete).
     *
     * @param int $serviceId
     * @return bool
     */
    public function deleteService(int $serviceId): bool
    {
        $service = $this->serviceRepository->findOrFail($serviceId);

        event(new ServiceDeletedEvent($service, auth()->user()));

        return $this->serviceRepository->delete($service);
    }
}
