<?php

namespace Payment\Repositories;

use Payment\Models\Proforma;
use Illuminate\Pagination\LengthAwarePaginator;

class ProformaRepository implements ProformaRepositoryInterface
{
    /**
     * Get all proformas for the current company (paginated).
     *
     * @param array<string, mixed> $params
     * @return LengthAwarePaginator
     */
    public function findAll(array $params = []): LengthAwarePaginator
    {
        $perPage = $params['per_page'] ?? 15;

        return Proforma::with(['services'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Find a proforma by ID or throw 404.
     *
     * @param int $id
     * @param array $with
     * @return Proforma
     */
    public function findOrFail(int $id, array $with = []): Proforma
    {
        $defaultWith = ['services'];

        return Proforma::query()
            ->with(!empty($with) ? $with : $defaultWith)
            ->findOrFail($id);
    }

    /**
     * Create a new proforma.
     *
     * @param array $data
     * @return Proforma
     */
    public function create(array $data): Proforma
    {
        return Proforma::create($data);
    }

    /**
     * Update a proforma and return the fresh model.
     *
     * @param Proforma $proforma
     * @param array $data
     * @return Proforma
     */
    public function update(Proforma $proforma, array $data): Proforma
    {
        $proforma->update($data);
        return $proforma->fresh();
    }

    /**
     * Delete a proforma (soft delete).
     *
     * @param Proforma $proforma
     * @return bool
     */
    public function delete(Proforma $proforma): bool
    {
        return $proforma->delete();
    }

    /**
     * Restore a soft-deleted proforma.
     *
     * @param int $id
     * @return Proforma
     */
    public function restore(int $id): Proforma
    {
        $proforma = Proforma::onlyTrashed()->findOrFail($id);
        $proforma->restore();

        return $proforma->load(['services']);
    }
}
