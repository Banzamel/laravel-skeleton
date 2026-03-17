<?php

namespace Payment\Repositories;

use Payment\Models\Proforma;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProformaRepositoryInterface
{
    public function findAll(array $params = []): LengthAwarePaginator;
    public function findOrFail(int $id, array $with = []): Proforma;
    public function create(array $data): Proforma;
    public function update(Proforma $proforma, array $data): Proforma;
    public function delete(Proforma $proforma): bool;
    public function restore(int $id): Proforma;
}
