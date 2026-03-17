<?php

namespace Payment\Repositories;

use Payment\Dtos\ServiceCreateDto;
use Payment\Models\Service;
use Illuminate\Database\Eloquent\Collection;

interface ServiceRepositoryInterface
{
    public function findAll(): Collection;
    public function findOrFail(int $serviceId): Service;
    public function create(ServiceCreateDto $dto): Service;
    public function update(Service $service, array $data): Service;
    public function delete(Service $service): bool;
}
