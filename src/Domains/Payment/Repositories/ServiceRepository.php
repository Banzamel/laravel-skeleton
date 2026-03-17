<?php

namespace Payment\Repositories;

use Payment\Dtos\ServiceCreateDto;
use Payment\Models\Service;
use Illuminate\Database\Eloquent\Collection;

class ServiceRepository implements ServiceRepositoryInterface
{
    /**
     * Get all services for the current company.
     *
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Service::all();
    }

    /**
     * Find a service by ID or throw 404.
     *
     * @param int $serviceId
     * @return Service
     */
    public function findOrFail(int $serviceId): Service
    {
        return Service::findOrFail($serviceId);
    }

    /**
     * Create a new service.
     *
     * @param ServiceCreateDto $dto
     * @return Service
     */
    public function create(ServiceCreateDto $dto): Service
    {
        return Service::create($dto->toArray());
    }

    /**
     * Update a service and return the fresh model.
     *
     * @param Service $service
     * @param array $data
     * @return Service
     */
    public function update(Service $service, array $data): Service
    {
        $service->update($data);
        return $service->fresh();
    }

    /**
     * Delete a service (soft delete).
     *
     * @param Service $service
     * @return bool
     */
    public function delete(Service $service): bool
    {
        return $service->delete();
    }
}
