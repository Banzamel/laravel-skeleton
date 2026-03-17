<?php

namespace Payment\Repositories;

use Payment\Models\Invoice;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    /**
     * Get all confirmed invoices for the current company (paginated).
     *
     * @param array<string, mixed> $params
     * @return LengthAwarePaginator
     */
    public function findAll(array $params = []): LengthAwarePaginator
    {
        $perPage = $params['per_page'] ?? 15;

        return Invoice::with(['services'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Find a confirmed invoice by ID or throw 404.
     *
     * @param int $id
     * @param array $with
     * @return Invoice
     */
    public function findOrFail(int $id, array $with = []): Invoice
    {
        $defaultWith = ['services'];

        return Invoice::query()
            ->with(!empty($with) ? $with : $defaultWith)
            ->findOrFail($id);
    }

    /**
     * Update an invoice and return the fresh model.
     *
     * @param Invoice $invoice
     * @param array $data
     * @return Invoice
     */
    public function update(Invoice $invoice, array $data): Invoice
    {
        $invoice->update($data);
        return $invoice->fresh();
    }
}
