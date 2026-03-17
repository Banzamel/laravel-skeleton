<?php

namespace Payment\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Payment\Dtos\ServiceCreateDto;
use Payment\Dtos\ServiceUpdateDto;
use Payment\Models\Service;

interface ServiceManagementServiceInterface
{
    /**
     * Get all services for the company.
     *
     * @return Collection
     */
    public function getAllServices(): Collection;

    /**
     * Get a service by ID.
     *
     * @param int $serviceId
     * @return Service
     */
    public function getServiceById(int $serviceId): Service;

    /**
     * Create a new service.
     *
     * @param ServiceCreateDto $dto
     * @return Service
     */
    public function createService(ServiceCreateDto $dto): Service;

    /**
     * Update an existing service.
     *
     * @param int $serviceId
     * @param ServiceUpdateDto $dto
     * @return Service
     */
    public function updateService(int $serviceId, ServiceUpdateDto $dto): Service;

    /**
     * Delete a service (soft delete).
     *
     * @param int $serviceId
     * @return bool
     */
    public function deleteService(int $serviceId): bool;
}
